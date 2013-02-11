<?php
/*
* phpMyDumper
* -------------
* Version: 1.10
* Copyright (c) 2009 by Micky Holdorf
* Holdorf.dk/Software - micky.holdorf@gmail.com
* GNU Public License http://opensource.org/licenses/gpl-license.php
*
* -------------------------------------------
* Aug 2012
* upgraded to mysqli by ionutvmi@gmail.com
* modified to fit wap phpmyadmin
*/

class phpMyDumper {
	/**
	* @access private
	*/
	var $database = null;
	var $compress = null;
	var $hexValue = null;
	var $dropTable = null;
	var $createTable = null;
	var $tableData = null;
	var $expInsert = null;
	var $phpMyAdmin = null;
	var $utf8 = null;
	var $autoincrement = null;

	var $filename = null;
	var $file = null;
	var $filestream = null;
	var $outputTofile = false;
	var $isWritten = false;
	var $tables = array();
	var $rds = false;
	var $query = false;

	/**
	* Class constructor
	* @param string $db The database name
	* @param string $connection The database connection handler
	* @param boolean $compress It defines if the output/import file is compressed (gzip) or not
	* @param string $filepath The file where the dump will be written
	*/
	function phpMyDumper($dbn=null, $filepath='dump.php', $compress=false, $tables=false, $rds=false,$query=false) {
		$this->compress = $compress;
		$this->tables = $tables;
		$this->rds = $rds;
		$this->query = $query;

		$this->hexValue = false;
		$this->dropTable = true;
		$this->createTable = false;
		$this->tableData = false;
		$this->expInsert = false;
		$this->phpMyAdmin = true;
		$this->utf8 = true;
		$this->autoincrement = false;

		$this->outputTofile = ( $filepath!='' ) ? true : false;
		if ( $this->outputTofile && !$this->setOutputFile($filepath) ) {
			//if filepath is null, then we want stream
			return false;
		}

		return $this->setDatabase($dbn);
	}

	/**
	* Sets the database to work on
	* @param string $db The database name
	*/
	function setDatabase($dbn){
	global $db;
		$this->database = $dbn;
		if ( !$db->select_db($this->database) )
			return false;
		return true;
  	}

	/**
	* Sets the output file
	* @param string $filepath The file where the dump will be written
	*/
	function setOutputFile($filepath) {
		if ( $this->isWritten )
			return false;
		echo "Creating file '".$filepath."': ";
		$this->filename = $filepath;
		$this->file = $this->openFile($this->filename);
		echo " DONE!\n";
		return $this->file;
  	}
	
	/**
 	* Writes to file all the selected database tables structure with SHOW CREATE TABLE
	* @param string $table The table name
	*/
	function getTableStructure($table) {
	global $db;
		// Header
		$structure = "\n-- --------------------------------------------------------\n";
		$structure .= "-- \n";
		$structure .= "-- Table structure for table ".PMA_bkq($table)."\n";
		$structure .= "-- \n\n";

		// Dump Structure
		if ( $this->dropTable )
			$structure .= "DROP TABLE IF EXISTS ".PMA_bkq($table).";\n";
		$records = $db->query("SHOW CREATE TABLE ".PMA_bkq($table));
		if ( $records->num_rows == 0 )
			return false;
		while ( $record = $records->fetch_assoc() ) {
			$structure .= $record['Create Table'];
		}
		$records = $db->query("SHOW TABLE STATUS LIKE '".$db->real_escape_string($table)."'");
		while ( $row = $records->fetch_assoc() ) {
			if ($this->autoincrement AND $row['Name']==$table AND $row['Auto_increment']!='') {
				$structure .= " AUTO_INCREMENT=".$row['Auto_increment'];
			}
		}
		$structure .= ";\n";
		$this->saveToFile($this->file,$structure);
  	}

	/**
	* Writes to file the $table's data
	* @param string $table The table name
	* @param boolean $hexValue It defines if the output is base 16 or not
	*/
	function getTableData($table,$hexValue = true) {
	global $db;
		// Header
		$data = "\n-- --------------------------------------------------------\n";
		$data .= "-- \n";
		$data .= "-- Dumping data for table ".PMA_bkq($table)."\n";
		$data .= "-- \n\n";
		$data1= $data;
			
		// Field names
		if ($this->expInsert || $this->hexValue) {
			$records = $db->query("SHOW FIELDS FROM ".PMA_bkq($table));
			$num_fields = $records->num_rows;
			if ( $num_fields == 0 )
				return false;
			$hexField = array();

			$insertStatement = "INSERT INTO ".PMA_bkq($table)." (";
			$selectStatement = "SELECT ";
			for ($x = 0; $x < $num_fields; $x++) {
				$record = mysqli_fetch_assoc($records);
				if ( ($hexValue) && ($this->isTextValue($record['Type'])) ) {
					$selectStatement .= 'HEX(`'.$record['Field'].'`)';
					$hexField [$x] = true;
				}
				else
					$selectStatement .= '`'.$record['Field'].'`';
				
				$insertStatement .= '`'.$record['Field'].'`';
				$insertStatement .= ", ";
				$selectStatement .= ", ";
				
			}
			$insertStatement = @substr($insertStatement,0,-2).') VALUES';
			$selectStatement = @substr($selectStatement,0,-2).' FROM `'.$table.'`';
		}
		
		if (!$this->expInsert)
			$insertStatement = "INSERT INTO ".PMA_bkq($table)." VALUES";
		
		if (!$this->hexValue)
			$selectStatement = "SELECT * FROM ".PMA_bkq($table)." ".$this->rds;
			
		if ($this->query){
			$selectStatement = $this->query;
			$insertStatement = "INSERT INTO ".PMA_bkq($table)." ";
			}
		// Dump data
		$records = $db->query($selectStatement);
		$num_rows = $records->num_rows;
		$num_fields = $records->field_count;
		
		while($_f = $records->fetch_field()){
			$_fields[] = PMA_bkq($_f->name);
		}
		$procent = 0;
		for ($i = 1; $i <= $num_rows; $i++) {
			$data .= $insertStatement;
			$record = $records->fetch_assoc();
			$data .= ' (';
			$data2 .= ' (';
			for ($j = 0; $j < $num_fields; $j++) {
				$field_name = $records->fetch_field_direct($j);
				$field_name = $field_name->name;
				if (is_null($record[$field_name])) {
				 	$data .= "NULL";
				 	$data2 .= "NULL";
				} 
				else {
					if ( isset($hexField[$j]) && (@strlen($record[$field_name]) > 0) ) {
						$data .= "0x".$record[$field_name];
						$data2 .= "0x".$record[$field_name];
					}
					else {
						$data .= '\''.@str_replace('\"','"',$db->real_escape_string($record[$field_name])).'\'';
						$data2 .= '\''.@str_replace('\"','"',$db->real_escape_string($record[$field_name])).'\'';
					}
				}
				$data .=  ',';
				$data2 .=  ',';
			}
			if ($this->query){
				$data = (!$y ? $data1 : "").$insertStatement."(".implode(",",$_fields).") VALUES ".$data2;
				$y=1;
			}
			 
			$data = @substr($data,0,-1).");\n";

			$this->saveToFile($this->file,$data);
			// cleaning searvice :)
			$data = $data1 = $data2 = '';
		}
	}

 	/**
	* Writes to file all the selected database tables structure
	* @return boolean
	*/
	function getDatabaseStructure() {
	global $db;
		$records = $db->query('SHOW TABLES');
		if ( $records->num_rows == 0 )
			return false;
		while ( $record = $records->fetch_row() ) {
			echo "Exporting table structure for '".$record[0]."': ";
			$this->getTableStructure($record[0]);
			echo " DONE!\n";
		}
		return true;
 	}

	/**
	* Writes to file all the selected database tables data
	* @param boolean $hexValue It defines if the output is base-16 or not
	*/
	function getDatabaseData($hexValue = true) {
	global $db;
		$records = $db->query('SHOW TABLES');
		if ( $records->num_rows == 0 )
			return false;
		while ( $record = $records->fetch_row() ) {
			if ($this->filename) echo "Exporting table data for '".$record[0]."': ";
			$this->getTableData($record[0],$hexValue);
			if ($this->filename) echo " DONE!\n";
		}
  	}

	/**
	* Writes to file all the selected database tables data
	* @param boolean $hexValue It defines if the output is base-16 or not
	*/
	function getDatabaseStructureData($hexValue = true){
	global $db;
		$records = $db->query('SHOW TABLES');
		if ( $records->num_rows == 0 )
			return false;
		while ( $record = $records->fetch_row() ) {
			if((in_array($record[0],$this->tables) && $this->tables != false) || $this->tables == false){ 
				if ( $this->createTable) {
					if ($this->filename) echo "Exporting table structure for '".$record[0]."': ";
					$this->getTableStructure($record[0]);
					if ($this->filename) echo " DONE!\n";
				}
				if ( $this->tableData) {
					if ($this->filename) echo "Exporting table data for '".$record[0]."': ";
					$this->getTableData($record[0],$hexValue);
					if ($this->filename) echo " DONE!\n";
				}
			}
		}// end while
  	}

	/**
	* Writes the selected database to file 
	*/
	function doDump($doDB = false,$i=1) {
	global $db, $pma;
		if ( !$this->setDatabase($this->database) )
			return false;

		if ( $this->utf8 ) {
			$encoding = $db->query("SET NAMES 'utf8'");
		}
		if($i<2) {
			$cur_time=date("Y-m-d H:i");
			$server_info = $db->get_server_info();
			$this->saveToFile($this->file,"-- Wap PhpMyAdmin $pma->version\n");
			$this->saveToFile($this->file,"-- http://master-land.net/phpmyadmin \n");
			$this->saveToFile($this->file,"-- Generation Time: $cur_time\n");
			$this->saveToFile($this->file,"-- MySQL Server Version: $server_info\n");
			$this->saveToFile($this->file,"-- PHP Version: ".phpversion()."\n\n");
		}
		if($doDB) {
			$this->saveToFile($this->file,"-- --------------------------------------------------------\n--\n-- Database: ".PMA_bkq($this->database)."\n-- \n");
			$create_query = "CREATE DATABASE ".PMA_bkq($this->database);
			$collation=$db->query('SELECT DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = \'' . $db->real_escape_string($this->database) . '\' LIMIT 1')->fetch_row();
			$collation = $collation[0];
			if (strpos($collation, '_')) {
			$create_query .= ' DEFAULT CHARACTER SET ' . substr($collation, 0, strpos($collation, '_')) . ' COLLATE ' . $collation;
			} else {
			$create_query .= ' DEFAULT CHARACTER SET ' . $collation;
			}
			$create_query.=";\nUSE ".PMA_bkq($this->database).";\n\n";
			$this->saveToFile($this->file,$create_query);
		}else {
			$this->saveToFile($this->file,"-- Database: ".PMA_bkq($this->database)."\n\n");
		}

		if ($this->phpMyAdmin) {
			$this->getDatabaseStructureData($this->hexValue);
		}
		else {	
			if ( $this->createTable )
				$this->getDatabaseStructure();
			if ( $this->tableData )
				$this->getDatabaseData($this->hexValue);
		}
		
		if ($this->outputTofile){
			$this->closeFile($this->file);
			return true;
		}
		else {
			return $this->filestream;
		}
	}

 	/**
	* @access private
	*/
	function isTextValue($field_type) {
		switch ($field_type) {
			case "tinytext":
			case "text":
			case "mediumtext":
			case "longtext":
			case "binary":
			case "varbinary":
			case "tinyblob":
			case "blob":
			case "mediumblob":
			case "longblob":
				return True;
				break;
			default:
				return False;
		}
	}
	
	/**
	* @access private
	*/
	function openFile($filename) {
		$file = false;
		if ( $this->compress )
			$file = @gzopen($filename, "w9");
		else
			$file = @fopen($filename, "w");
		return $file;
	}

	/**
	* @access private
	*/
	function saveToFile($file, $data) {
		if ($this->outputTofile){
			if ( $this->compress )
				@gzwrite($file, $data);
			else
				@fwrite($file, $data);
			$this->isWritten = true;
		}
		else {
			$this->saveToStream($data);
		}
	}

	/**
	* @access private
	*/
	function saveToStream($data) {
		$this->filestream .= $data;
	}
	
	/**
	* @access private
	*/
	function closeFile($file) {
		if ( $this->compress )
			@gzclose($file);
		else
			@fclose($file);
	}
}
?>
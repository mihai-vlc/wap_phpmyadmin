<?php

/*
*
* SEP-2012
* master-land.net
* MySQLi by ionutvmi@gmail.com
* IMPORTANT: THIS IS *NOT* A FULL REPLACEMENT FOR MYSQLi
* i only written this to keep wap phpmyadmin compatible with mysql
* if you used only oop in your code this may save your day
* fell free to use it as you wish as long as you don't sell it
* if you bring improvements to this please send me a copy: ionutvmi@gmail.com
*
* 
*/

class mysqli {
	var $error = null;
	var $_result = array();
	var $current_result = 0;
	var $connect_errno = 0;
	var $connect_error = null;
	
	function __construct($host,$user,$pass){
		$c = @mysql_connect($host,$user,$pass);
		$this->connect_error = @mysql_error();
		$this->connect_errno = @mysql_errno();
		return $c;
	}
	function select_db($db){
		return @mysql_select_db($db);
	}
	function query($q){
		$result = @mysql_query($q);
		if($result == TRUE){
			return new mysqli_result($result);
		}else{
			$this->error = @mysql_error();
			return false;
		}
	}
	
	function multi_query($q){
		$delimiter = ';'; // here is the delimiter edit this if you have a more "special" query
		$lines= preg_split("/(\r\n|\n|\r)/", $q);
		foreach($lines as $line){
		$qr[]=$line;
			if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($qr)) === 1){
				$query = mysql_query(implode('',$qr));
				if (!$query){ 
					$this->error=@mysql_error();
					return false;
				}
				$this->_result[] = $query;
				$qr=array();
			}
		}
		
	if($query)
		return true;
	else
		return false;
	}
	function next_result(){
	if($this->_result[$this->current_result++])
		return $ionutvmi = true;
	else
		return false;
	}
	function store_result(){
			return new mysqli_result($this->_result[$this->current_result]);
	}
	
	function real_escape_string($string){
		return @mysql_real_escape_string($string);
	}
	function get_server_info(){
		return @mysql_get_server_info();
	}
}
class mysqli_result {
	var $result = null;
	var $num_rows = 0;
	var $field_count = 0;

	function __construct($result){
		$this->result=$result;
		$this->num_rows = @mysql_num_rows($result);
		$this->field_count = @mysql_num_fields($result);
		return TRUE;
	}
	function fetch_array(){
		return mysql_fetch_array($this->result);
	}
	function fetch_assoc(){
		return mysql_fetch_assoc($this->result);
	}
	function fetch_object(){
		return mysql_fetch_object($this->result);
	}
	function fetch_row(){
		return mysql_fetch_row($this->result);
	}
	function fetch_field(){
		return mysql_fetch_field($this->result);
	}
	function fetch_field_direct($i){
		return mysql_fetch_field($this->result,$i);
	}
	function fetch_fields(){
		for($i=0;$i<mysql_num_fields($this->result);++$i)
			$r[$i] = mysql_fetch_field($this->result,$i);
		return $r;
	}
}
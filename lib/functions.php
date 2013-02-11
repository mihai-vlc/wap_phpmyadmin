<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

function connect_db(&$db) {
global $pma;
if(!$pma->host) {	header("Location: index.php"); exit; }

$db = new mysqli($pma->host, $pma->user, $pma->pass);
/* check connection */
if ($db->connect_errno) {
    die($db->connect_error);
}
return $db;
}

function PMA_bkq($a_name)
{
    if (is_array($a_name)) {
        foreach ($a_name as &$data) {
            $data = PMA_backquote($data, $do_it);
        }
        return $a_name;
    }

    // '0' is also empty for php :-(
    if (strlen($a_name) && $a_name !== '*') {
        return '`' . str_replace('`', '``', $a_name) . '`';
    } else {
        return $a_name;
    }
} // end of the 'PMA_bkq()' function


function pma_img($src) {
global $pma;
if(!$_SESSION['noimg'])
return "<img class='icon' src='".$pma->tpl."style/img/$src'>";
}



    /*
        Function: highlight_sql
        Author: ME Wieringa <pholeron@hotmail.com>
        
        Description: Highlight your query on the fly
    */
    function highlight_sql($string)
    {

        $aKeywords = array(); 

        // SQL syntax
        $aKeywords[] = array('and', true); // keyword name (any string [a-zA-Z0-9_], or any character), keyword to next line (true or false, default: false), css class (default: 'keyword')
        $aKeywords[] = array('as', false);
        $aKeywords[] = array('asc', false);
        $aKeywords[] = array('binary', false);
        $aKeywords[] = array('by', false);
        $aKeywords[] = array('delete', true);
        $aKeywords[] = array('desc', false);
        $aKeywords[] = array('from', true);
        $aKeywords[] = array('having', true);
        $aKeywords[] = array('group', true);
        $aKeywords[] = array('insert', true);
        $aKeywords[] = array('in', true);
        $aKeywords[] = array('into', false);
        $aKeywords[] = array('join', false);
        $aKeywords[] = array('left', false);
        $aKeywords[] = array('like', false);
        $aKeywords[] = array('limit', true);
        $aKeywords[] = array('order', true);
        $aKeywords[] = array('on', false);
        $aKeywords[] = array('or', true);
        $aKeywords[] = array('right', false);
        $aKeywords[] = array('select', true);
        $aKeywords[] = array('set', true);
        $aKeywords[] = array('values', true);
        $aKeywords[] = array('where', true);
        $aKeywords[] = array('xor', true);

        // Operators
        $aKeywords[] = array('+', false, 'operator');
        $aKeywords[] = array('-', false, 'operator');
        $aKeywords[] = array('*', false, 'operator');
        $aKeywords[] = array('/', false, 'operator');
        $aKeywords[] = array('=', false, 'operator');
        $aKeywords[] = array('<', false, 'operator');
        $aKeywords[] = array('>', false, 'operator');
        $aKeywords[] = array('%', false, 'operator');
        $aKeywords[] = array('.', false, 'operator');
        $aKeywords[] = array(',', false, 'operator');


        $aKeywords[] = array('true', false, 'quoted');
        $aKeywords[] = array('false', false, 'quoted');
        $aKeywords[] = array('null', false, 'quoted');
        $aKeywords[] = array('unkown', false, 'quoted');



        // Split query into pieces (quoted values, ticked values, string and/or numeric values, and all others).
        $expr = '/(\'((\\\\.)|[^\\\\\\\'])*\')|(\`((\\\\.)|[^\\\\\\\`])*\`)|([a-z0-9_]+)|([\s\n]+)|(.)/i';
        preg_match_all($expr, $string, $matches);

        // Use a buffer to build up lines.
        $buffer = '';
        
        // Keep track of brackets to indent/outdent
        $iTab = 0;

        for($i = 0; $i < sizeof($matches[0]); $i++)
        {
            if(strcasecmp($match = $matches[0][$i], "") !== 0)
            {
                if(in_array($match, array("(", ")"))) // Bracket found
                {
                    $buffer = trim($buffer);

                    if(strlen($buffer) > 0)
                    {
                        $result .= $buffer . ' ';
                    }

                    $buffer = '';

                    if(strcasecmp($match, ")") === 0)
                    {
                        $iTab--;

                        if($iTab < 0)
                        {
                            $iTab = 0;
                        }

                        $result .= str_repeat('', 4 * $iTab) . '<span class="bracket">' . htmlentities($match) . '</span> ';
                    }
                    else // if(strcasecmp($match, "(") === 0)
                    {
                        $result .= str_repeat('', 4 * $iTab) . '<span class="bracket">' . htmlentities($match) . '</span> ';
                        $iTab++;
                    }
                }
                elseif(preg_match('/^[\s\n]+$/', $match)) // Space character(s)
                {
                    if(strlen($buffer) === 0)
                    {
                        // Ignore space character(s)!
                    }
                    else
                    {
                        $buffer .= ' ';
                    }
                }
                else
                {
                    $aKeyword = false;

                    for($j = 0; $j < sizeof($aKeywords); $j++)
                    {
                        if(strcasecmp($match, $aKeywords[$j][0]) === 0)
                        {
                            $aKeyword = $aKeywords[$j];
                            break;
                        }
                    }

                    if($aKeyword) // Keyword found
                    {
                        if(isset($aKeyword[1]) && $aKeyword[1] === true) // Keyword to next line
                        {
                            $buffer = trim($buffer);

                            if(strlen($buffer) > 0)
                            {
                                $result .= $buffer . ' ';
                            }

                            $buffer = ''; 
                        }

                        if(strlen($buffer) === 0) // Indent
                        {
                            $buffer .= str_repeat('', 4 * $iTab); 
                        }

                        $buffer .= '<span class="' . (isset($aKeyword[2]) ? $aKeyword[2] : 'keyword') . '">' . htmlentities(strtoupper($match)) . '</span>';
                    }
                    else
                    {
                        if(strlen($buffer) === 0) // Indent
                        {
                            $buffer = str_repeat('', 4 * $iTab);
                        }
						if(is_numeric($match))
							$buffer .= '<span class="numeric">' . htmlentities($match) . '</span>';
							else
                        if((strcasecmp(substr($match, 0, 1), "'") === 0)) // Quoted value or number
                        {
                            $buffer .= '<span class="quoted">' . htmlentities($match) . '</span>';
                        }
                        elseif((strcasecmp(substr($match, 0, 1), "`") === 0) || preg_match('/[a-z0-9_]+/i', $match)) // Ticked value or unquoted string (table/column name?!)
                        {
                            $buffer .= '<span class="ticked">' . htmlentities($match) . '</span>';
                        }
                        else // All other chars
                        {
                            $buffer .= htmlentities($match);
                        }
                    }
                }
            }
        }

        $buffer = trim($buffer);

        if(strlen($buffer) > 0)
        {
            $result .= $buffer;
        }

        return '<code class="sql">' . $result . '</code>';
    }

function convert($size)
{
$unit=array('B','KB','MB','GB','TB','PB');
return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function make_select($type,$name,$vars,$selected=false,$extra=''){
$content_cells = "<select name='$name'>".$extra;
foreach ($vars as $col_goup => $column_type) {

        if (is_array($column_type)) {
            $content_cells .= '<optgroup label="' . htmlspecialchars($col_goup) . '">';
            foreach ($column_type as $col_group_type) {
                $content_cells .= '<option value="'. $col_group_type . '"';
                if (strtoupper($selected) == strtoupper($col_group_type)) {
                    $content_cells .= ' selected="selected"';
                }
                $content_cells .= '>' . $col_group_type . '</option>';
            }
            $content_cells .= '</optgroup>';
            continue;
        }

        $content_cells .= '<option value="'. ($type==1 ? $col_goup: $column_type) . '"';
        if ((strtoupper($selected) == strtoupper($column_type)) OR (strtoupper($selected) == strtoupper($col_goup) && $type==1)) {
            $content_cells .= ' selected="selected"';
        }
        $content_cells .= '>' . $column_type . '</option>';
    } // end for
$content_cells.="</select>";
	return $content_cells;
}


function getChar() {
global $db;
$res = $db->query('SHOW CHARACTER SET;');
$mysql_charsets = array();
while ($row = $res->fetch_assoc()) {
        $mysql_charsets[] = $row['Charset'];
        }
    sort($mysql_charsets, SORT_STRING);
    $mysql_collations = array_flip($mysql_charsets);
    $res = $db->query('SHOW COLLATION;');
    while ($row = $res->fetch_assoc()) {
        if (!is_array($mysql_collations[$row['Charset']])) {
            $mysql_collations[$row['Charset']] = array($row['Collation']);
        } else {
            $mysql_collations[$row['Charset']][] = $row['Collation'];
        }
		}
		foreach ($mysql_collations AS $key => $value) {
        sort($mysql_collations[$key], SORT_STRING);
        reset($mysql_collations[$key]);

		}
return $mysql_collations;
}
function stripslashes_recursive($value) {
    if (is_array($value)) {
        foreach ($value as $index => $val) {
            $value[$index] = stripslashes_recursive($val);
        }
        return $value;
    } else {
        return stripslashes($value);
    }
}

function remove_magic_quotes()
{
    if( get_magic_quotes_gpc() ) {
		$_GET = stripslashes_recursive($_GET);
		$_POST = stripslashes_recursive($_POST);
    }
}
function fwrite_stream($fp, $string) {
    for ($written = 0; $written < strlen($string); $written += $fwrite) {
        $fwrite = fwrite($fp, substr($string, $written));
        if ($fwrite === false) {
            return $written;
        }
    }
    return $written;
}

/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
  //if the zip file already exists and overwrite is false, return false
  if(file_exists($destination) && !$overwrite) { return false; }
  //vars
  $valid_files = array();
  //if files were passed in...
  if(is_array($files)) {
    //cycle through each file
    foreach($files as $file) {
      //make sure the file exists
      if(file_exists($file)) {
        $valid_files[] = $file;
      }
    }
  }
  //if we have good files...
  if(count($valid_files)) {
    //create the archive
    $zip = new ZipArchive();
    if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
      return false;
    }
    //add the files
    foreach($valid_files as $file) {
      $zip->addFile($file,str_replace('data/','',$file));
    }
    //debug
    //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
    
    //close the zip -- done!
    $zip->close();
    
    //check to make sure the file exists
    return file_exists($destination);
  }
  else
  {
    return false;
  }
}
function zipIsValid($path) {
  $zip = zip_open($path);
  if (is_resource($zip)) {
    // it's ok
    zip_close($zip); // always close handle if you were just checking
    return true;
  } else {
    return false;
  }
}
function get_max_upl() {
$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
return min($max_upload, $max_post, $memory_limit);
}


/**
 * Extracts the various parts from a field type spec
 *
 * @uses    strpos()
 * @uses    chop()
 * @uses    substr()
 * @param   string $fieldspec
 * @return  array associative array containing type, spec_in_brackets
 *          and possibly enum_set_values (another array)
 * @author  Marc Delisle
 * @author  Joshua Hogendorn
 */
function PMA_extractFieldSpec($fieldspec) {
    $first_bracket_pos = strpos($fieldspec, '(');
    if ($first_bracket_pos) {
        $spec_in_brackets = chop(substr($fieldspec, $first_bracket_pos + 1, (strrpos($fieldspec, ')') - $first_bracket_pos - 1)));
        // convert to lowercase just to be sure
        $type = strtolower(chop(substr($fieldspec, 0, $first_bracket_pos)));
    } else {
        $type = $fieldspec;
        $spec_in_brackets = '';
    }

    if ('enum' == $type || 'set' == $type) {
        // Define our working vars
        $enum_set_values = array();
        $working = "";
        $in_string = false;
        $index = 0;

        // While there is another character to process
        while (isset($fieldspec[$index])) {
            // Grab the char to look at
            $char = $fieldspec[$index];

            // If it is a single quote, needs to be handled specially
            if ($char == "'") {
                // If we are not currently in a string, begin one
                if (! $in_string) {
                    $in_string = true;
                    $working = "";
                // Otherwise, it may be either an end of a string, or a 'double quote' which can be handled as-is
                } else {
                // Check out the next character (if possible)
                    $has_next = isset($fieldspec[$index + 1]);
                    $next = $has_next ? $fieldspec[$index + 1] : null;

                // If we have reached the end of our 'working' string (because there are no more chars, or the next char is not another quote)
                    if (! $has_next || $next != "'") {
                        $enum_set_values[] = $working;
                        $in_string = false;

                    // Otherwise, this is a 'double quote', and can be added to the working string
                    } elseif ($next == "'") {
                        $working .= "'";
                        // Skip the next char; we already know what it is
                        $index++;
                    }
                }
            // escaping of a quote?
            } elseif ('\\' == $char && isset($fieldspec[$index + 1]) && "'" == $fieldspec[$index + 1]) {
                $working .= "'";
                $index++;
            // Otherwise, add it to our working string like normal
            } else {
                $working .= $char;
            }
            // Increment character index
            $index++;
        } // end while
    } else {
        $enum_set_values = array();
    }

    return array(
        'type' => $type,
        'spec_in_brackets' => $spec_in_brackets,
        'enum_set_values'  => $enum_set_values
    );
}

function get_hidden($ignore=array()) {
	foreach($_GET as $k=>$v){
		if(!in_array($k,$ignore))
			$h.="<input type='hidden' name='".urlencode($k)."' value='".urlencode($v)."'>";
	}
	return $h;
}

/*
* figure out what is making a record unique
* if it has a primary or unique key(s) return that
* if not use all columns
*/
function getUniqueCondition($result,$row)
{
	global $db;
    $fields = $result->fetch_fields();
	
    foreach ($fields as $k => $field) {
		if(defined('MYSQLI_PRI_KEY_FLAG') && defined('MYSQLI_UNIQUE_KEY_FLAG')) {
			$_primary_key = (int) (bool) ($field->flags & MYSQLI_PRI_KEY_FLAG);
			$_unique_key = (int) (bool) ($field->flags & MYSQLI_UNIQUE_KEY_FLAG);
		}else{
			$_primary_key = (int) (bool) ($field->primary_key);
			$_unique_key = (int) (bool) ($field->unique_key);
		}
		$c[]="(".PMA_bkq($field->name)." = '".$db->real_escape_string($row[$field->name])."')";
		if($_primary_key)
			$primary[]="(".PMA_bkq($field->name)." = '".$db->real_escape_string($row[$field->name])."')";
		elseif($_unique_key)
			$unique[]="(".PMA_bkq($field->name)." = '".$db->real_escape_string($row[$field->name])."')";
	}
	
	if($primary)
		$result=implode(" AND ",$primary);
	elseif($unique)
		$result=implode(" AND ",$unique);
	else
		$result=implode(" AND ",$c);
 
 		return $result;
}

// pagination function
function pag($total,$currentPage,$baseLink,$nextPrev=true,$limit=10) { 
	global $lang;
	if(!$total OR !$currentPage OR !$baseLink) { 
	return false; } //Total Number of pages 
	$totalPages = ceil($total/$limit); //Text to use after number of pages 
	$txtPagesAfter = ($totalPages==1)? " $lang->Page": " $lang->Pages"; //Start off the list. 
	$txtPageList = $totalPages.$txtPagesAfter .': ' ; //Show only 3 pages before current page(so that we don't have too many pages) 
	$min = ($currentPage - 3 < $totalPages && $currentPage-3 > 0) ? $currentPage-3 : 1; //Show only 3 pages after current page(so that we don't have too many pages) 
	$max = ($currentPage + 3 > $totalPages) ? $totalPages : $currentPage+3; //Variable for the actual page links 
	$pageLinks = ""; //Loop to generate the page links 
	for($i=$min;$i<=$max ;$i++) { 
	if($currentPage==$i) { //Current Page 
	$pageLinks .= ' <b class="selected">'.$i.'</b> ' ; } 
	else { $pageLinks .= ' <a href="'.$baseLink.$i.'" class="page">'.$i.'</a> ' ; } } 
	if($nextPrev ) { //Next and previous links 
	$next = ($currentPage + 1 > $totalPages) ? false : '<a href="'.$baseLink.($currentPage + 1) .'">'.$lang->Next.'</a>' ; 
	$prev = ($currentPage - 1 <= 0 ) ? false : '<a href="'.$baseLink.($currentPage - 1).'">'.$lang->Prev.'</a>' ; } 
	$first= ($currentPage > 2) ? '<a href="'.$baseLink.'1">'.$lang->First.'</a> ': false ;

	 $last= ($currentPage < ($totalPages - 2)) ? " <a href='".$baseLink.$totalPages."'>".$lang->Last."</a> " : false ;

	return $txtPageList.$first.$prev.$pageLinks.$next.$last; 
} 


// this should check if data is an 
// sql function to remove ' from query
// i'm pretty sure there is a better way of doing this
// i will come back on this when i can
function isSqlFunction($str){
	global $_var;
	// check if it's exact
	$str=trim(strtoupper($str));
	if(in_array($str,$_var->sql_function_name))
	return true;
	// this needs to be improved
	return false;
	
}

?>
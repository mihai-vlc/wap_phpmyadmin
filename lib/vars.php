<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

$_var = new stdClass();
$_var->perp = array("10","20","30","40","50","60","70","80","90");
$_var->home = "<a href='main.php'>".(!$_SESSION['noimg'] ? "<img src='".$pma->tpl."style/img/b_home.png'>" : "").$lang->home."</a> &#187; ";
$_var->ColumnTypes = array(
    // most used
    'INT',
    'VARCHAR',
    'TEXT',
    'DATE',

    // numeric
    'NUMERIC' => array(
        'TINYINT',
        'SMALLINT',
        'MEDIUMINT',
        'INT',
        'BIGINT',
        '-',
        'DECIMAL',
        'FLOAT',
        'DOUBLE',
        'REAL',
        '-',
        'BIT',
        'BOOLEAN',
        'SERIAL',
    ),


    // Date/Time
    'DATE and TIME' => array(
        'DATE',
        'DATETIME',
        'TIMESTAMP',
        'TIME',
        'YEAR',
    ),

    // Text
    'STRING' => array(
        'CHAR',
        'VARCHAR',
        '-',
        'TINYTEXT',
        'TEXT',
        'MEDIUMTEXT',
        'LONGTEXT',
        '-',
        'BINARY',
        'VARBINARY',
        '-',
        'TINYBLOB',
        'MEDIUMBLOB',
        'BLOB',
        'LONGBLOB',
        '-',
        'ENUM',
        'SET',
    ),

    'SPATIAL' => array(
        'GEOMETRY',
        'POINT',
        'LINESTRING',
        'POLYGON',
        'MULTIPOINT',
        'MULTILINESTRING',
        'MULTIPOLYGON',
        'GEOMETRYCOLLECTION',
    ),
);

$_var->AttributeTypes = array(
   '',
   'BINARY',
   'UNSIGNED',
   'UNSIGNED ZEROFILL',
   'on update CURRENT_TIMESTAMP',
);
$_var->default_options = array(
        'NONE'              =>  $lang->none,
        'USER_DEFINED'      =>  $lang->as_defined,
        'NULL'              => 'NULL',
        'CURRENT_TIMESTAMP' => 'CURRENT_TIMESTAMP',
    );
$_var->index = array(
	'---',
	'PRIMARY',
	'UNIQUE',
	'INDEX',
	'FULLTEXT',
);

$_var->tb_do = array(
'export' => $lang->Export,
'empty' => $lang->Empty,
'drop' => $lang->Drop,
'check' => $lang->check_tb,
'optimize' => $lang->optimize_tb,
'repair' => $lang->repair_tb,
'analyze' => $lang->analyze_tb,
);
$_var->tb_br_do = array(
'' => $lang->With_selected,
'1' => $lang->Drop,
'2' => $lang->Export
);

$_var->sql_function_name = array (
    'ABS',
    'ACOS',
    'ADDDATE',
    'ADDTIME',
    'AES_DECRYPT',
    'AES_ENCRYPT',
    'AREA',                     // Area() polygon-property-functions.html
    'ASBINARY',                 // AsBinary()
    'ASCII',
    'ASIN',
    'ASTEXT',                   // AsText()
    'ATAN',
    'ATAN2',
    'AVG',
    'BDMPOLYFROMTEXT',          // BdMPolyFromText()
    'BDMPOLYFROMWKB',           // BdMPolyFromWKB()
    'BDPOLYFROMTEXT',           // BdPolyFromText()
    'BDPOLYFROMWKB',            // BdPolyFromWKB()
    'BENCHMARK',
    'BIN',
    'BIT_AND',
    'BIT_COUNT',
    'BIT_LENGTH',
    'BIT_OR',
    'BIT_XOR',                  // group-by-functions.html
    'BOUNDARY',                 // Boundary() general-geometry-property-functions.html
    'BUFFER',                   // Buffer()
    'CAST',
    'CEIL',
    'CEILING',
    'CENTROID',                 // Centroid() multipolygon-property-functions.html
    'CHAR',                     // string-functions.html
    'CHARACTER_LENGTH',
    'CHARSET',                  // information-functions.html
    'CHAR_LENGTH',
    'COALESCE',
    'COERCIBILITY',             // information-functions.html
    'COLLATION',                // information-functions.html
    'COMPRESS',                 // string-functions.html
    'CONCAT',
    'CONCAT_WS',
    'CONNECTION_ID',
    'CONTAINS',                 // Contains()
    'CONV',
    'CONVERT',
    'CONVERT_TZ',
    'CONVEXHULL',               // ConvexHull()
    'COS',
    'COT',
    'COUNT',
    'CRC32',                    // mathematical-functions.html
    'CROSSES',                  // Crosses()
    'CURDATE',
    'CURRENT_DATE',
    'CURRENT_TIME',
    'CURRENT_TIMESTAMP',
    'CURRENT_USER',
    'CURTIME',
    'DATABASE',
    'DATE',                     // date-and-time-functions.html
    'DATEDIFF',                 // date-and-time-functions.html
    'DATE_ADD',
    'DATE_DIFF',
    'DATE_FORMAT',
    'DATE_SUB',
    'DAY',
    'DAYNAME',
    'DAYOFMONTH',
    'DAYOFWEEK',
    'DAYOFYEAR',
    'DECODE',
    'DEFAULT',                  // miscellaneous-functions.html
    'DEGREES',
    'DES_DECRYPT',
    'DES_ENCRYPT',
    'DIFFERENCE',               // Difference()
    'DIMENSION',                // Dimension() general-geometry-property-functions.html
    'DISJOINT',                 // Disjoint()
    'DISTANCE',                 // Distance()
    'ELT',
    'ENCODE',
    'ENCRYPT',
    'ENDPOINT',                 // EndPoint() linestring-property-functions.html
    'ENVELOPE',                 // Envelope() general-geometry-property-functions.html
    'EQUALS',                   // Equals()
    'EXP',
    'EXPORT_SET',
    'EXTERIORRING',             // ExteriorRing() polygon-property-functions.html
    'EXTRACT',
    'EXTRACTVALUE',             // ExtractValue() xml-functions.html
    'FIELD',
    'FIND_IN_SET',
    'FLOOR',
    'FORMAT',
    'FOUND_ROWS',
    'FROM_DAYS',
    'FROM_UNIXTIME',
    'GEOMCOLLFROMTEXT',         // GeomCollFromText()
    'GEOMCOLLFROMWKB',          // GeomCollFromWKB()
    'GEOMETRYCOLLECTION',       // GeometryCollection()
    'GEOMETRYCOLLECTIONFROMTEXT',   // GeometryCollectionFromText()
    'GEOMETRYCOLLECTIONFROMWKB',    // GeometryCollectionFromWKB()
    'GEOMETRYFROMTEXT',         // GeometryFromText()
    'GEOMETRYFROMWKB',          // GeometryFromWKB()
    'GEOMETRYN',                // GeometryN() geometrycollection-property-functions.html
    'GEOMETRYTYPE',             // GeometryType() general-geometry-property-functions.html
    'GEOMFROMTEXT',             // GeomFromText()
    'GEOMFROMWKB',              // GeomFromWKB()
    'GET_FORMAT',
    'GET_LOCK',
    'GLENGTH',                  // GLength() linestring-property-functions.html
    'GREATEST',
    'GROUP_CONCAT',
    'GROUP_UNIQUE_USERS',
    'HEX',
    'HOUR',
    'IF',                       //control-flow-functions.html
    'IFNULL',
    'INET_ATON',
    'INET_NTOA',
    'INSERT',                   // string-functions.html
    'INSTR',
    'INTERIORRINGN',            // InteriorRingN() polygon-property-functions.html
    'INTERSECTION',             // Intersection()
    'INTERSECTS',               // Intersects()
    'INTERVAL',
    'ISCLOSED',                 // IsClosed() multilinestring-property-functions.html
    'ISEMPTY',                  // IsEmpty() general-geometry-property-functions.html
    'ISNULL',
    'ISRING',                   // IsRing() linestring-property-functions.html
    'ISSIMPLE',                 // IsSimple() general-geometry-property-functions.html
    'IS_FREE_LOCK',
    'IS_USED_LOCK',             // miscellaneous-functions.html
    'LAST_DAY',
    'LAST_INSERT_ID',
    'LCASE',
    'LEAST',
    'LEFT',
    'LENGTH',
    'LINEFROMTEXT',             // LineFromText()
    'LINEFROMWKB',              // LineFromWKB()
    'LINESTRING',               // LineString()
    'LINESTRINGFROMTEXT',       // LineStringFromText()
    'LINESTRINGFROMWKB',        // LineStringFromWKB()
    'LN',
    'LOAD_FILE',
    'LOCALTIME',
    'LOCALTIMESTAMP',
    'LOCATE',
    'LOG',
    'LOG10',
    'LOG2',
    'LOWER',
    'LPAD',
    'LTRIM',
    'MAKEDATE',
    'MAKETIME',
    'MAKE_SET',
    'MASTER_POS_WAIT',
    'MAX',
    'MBRCONTAINS',              // MBRContains()
    'MBRDISJOINT',              // MBRDisjoint()
    'MBREQUAL',                 // MBREqual()
    'MBRINTERSECTS',            // MBRIntersects()
    'MBROVERLAPS',              // MBROverlaps()
    'MBRTOUCHES',               // MBRTouches()
    'MBRWITHIN',                // MBRWithin()
    'MD5',
    'MICROSECOND',
    'MID',
    'MIN',
    'MINUTE',
    'MLINEFROMTEXT',            // MLineFromText()
    'MLINEFROMWKB',             // MLineFromWKB()
    'MOD',
    'MONTH',
    'MONTHNAME',
    'NOW',
    'MPOINTFROMTEXT',           // MPointFromText()
    'MPOINTFROMWKB',            // MPointFromWKB()
    'MPOLYFROMTEXT',            // MPolyFromText()
    'MPOLYFROMWKB',             // MPolyFromWKB()
    'MULTILINESTRING',          // MultiLineString()
    'MULTILINESTRINGFROMTEXT',  // MultiLineStringFromText()
    'MULTILINESTRINGFROMWKB',   // MultiLineStringFromWKB()
    'MULTIPOINT',               // MultiPoint()
    'MULTIPOINTFROMTEXT',       // MultiPointFromText()
    'MULTIPOINTFROMWKB',        // MultiPointFromWKB()
    'MULTIPOLYGON',             // MultiPolygon()
    'MULTIPOLYGONFROMTEXT',     // MultiPolygonFromText()
    'MULTIPOLYGONFROMWKB',      // MultiPolygonFromWKB()
    'NAME_CONST',               // NAME_CONST()
    'NOW',                      // NOW()
    'NULLIF',
    'NUMGEOMETRIES',            // NumGeometries() geometrycollection-property-functions.html
    'NUMINTERIORRINGS',         // NumInteriorRings() polygon-property-functions.html
    'NUMPOINTS',                // NumPoints() linestring-property-functions.html
    'OCT',
    'OCTET_LENGTH',
    'OLD_PASSWORD',
    'ORD',
    'OVERLAPS',                 // Overlaps()
    'PASSWORD',
    'PERIOD_ADD',
    'PERIOD_DIFF',
    'PI',
    'POINT',                    // Point()
    'POINTFROMTEXT',            // PointFromText()
    'POINTFROMWKB',             // PointFromWKB()
    'POINTN',                   // PointN() inestring-property-functions.html
    'POINTONSURFACE',           // PointOnSurface() multipolygon-property-functions.html
    'POLYFROMTEXT',             // PolyFromText()
    'POLYFROMWKB',              // PolyFromWKB()
    'POLYGON',                  // Polygon()
    'POLYGONFROMTEXT',          // PolygonFromText()
    'POLYGONFROMWKB',           // PolygonFromWKB()
    'POSITION',
    'POW',
    'POWER',
    'QUARTER',
    'QUOTE',
    'RADIANS',
    'RAND',
    'RELATED',                  // Related()
    'RELEASE_LOCK',
    'REPEAT',
    'REPLACE',                  // string-functions.html
    'REVERSE',
    'RIGHT',
    'ROUND',
    'ROW_COUNT',                // information-functions.html
    'RPAD',
    'RTRIM',
    'SCHEMA',                   // information-functions.html
    'SECOND',
    'SEC_TO_TIME',
    'SESSION_USER',
    'SHA',
    'SHA1',
    'SIGN',
    'SIN',
    'SLEEP',                    // miscellaneous-functions.html
    'SOUNDEX',
    'SPACE',
    'SQRT',
    'SRID',                     // general-geometry-property-functions.html
    'STARTPOINT',               // StartPoint() linestring-property-functions.html
    'STD',
    'STDDEV',
    'STDDEV_POP',               // group-by-functions.html
    'STDDEV_SAMP',              // group-by-functions.html
    'STRCMP',
    'STR_TO_DATE',
    'SUBDATE',
    'SUBSTR',
    'SUBSTRING',
    'SUBSTRING_INDEX',
    'SUBTIME',
    'SUM',
    'SYMDIFFERENCE',            // SymDifference()
    'SYSDATE',
    'SYSTEM_USER',
    'TAN',
    'TIME',
    'TIMEDIFF',
    'TIMESTAMP',
    'TIMESTAMPADD',
    'TIMESTAMPDIFF',
    'TIME_FORMAT',
    'TIME_TO_SEC',
    'TOUCHES',                  // Touches()
    'TO_DAYS',
    'TRIM',
    'TRUNCATE',                 // mathematical-functions.html
    'UCASE',
    'UNCOMPRESS',               // string-functions.html
    'UNCOMPRESSED_LENGTH',      // string-functions.html
    'UNHEX',                    // string-functions.html
    'UNIQUE_USERS',
    'UNIX_TIMESTAMP',
    'UPDATEXML',                // UpdateXML() xml-functions.html
    'UPPER',
    'USER',
    'UTC_DATE',
    'UTC_TIME',
    'UTC_TIMESTAMP',
    'UUID',                     // miscellaneous-functions.html
    'VARIANCE',                 // group-by-functions.html
    'VAR_POP',                  // group-by-functions.html
    'VAR_SAMP',                 // group-by-functions.html
    'VERSION',
    'WEEK',
    'WEEKDAY',
    'WEEKOFYEAR',
    'WITHIN',                   // Within()
    'X',                        // point-property-functions.html
    'Y',                        // point-property-functions.html
    'YEAR',
    'YEARWEEK'
);



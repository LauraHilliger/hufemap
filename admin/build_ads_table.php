<?php
session_start();
require_once ('../config.php');
/*
COPYRIGHT 2008 - see www.milliondollarscript.com for a list of authors

This file is part of the Million Dollar Script.

Million Dollar Script is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Million Dollar Script is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with the Million Dollar Script.  If not, see <http://www.gnu.org/licenses/>.

*/
?>

<?php

$sql = "SELECT * FROM `form_fields` where form_id=1 AND field_type != 'BLANK' AND field_type !='SEPERATOR' AND field_type !='NOTE' ";
$result = mysql_query($sql) or die (mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$fields[$row[field_id]]['field_id'] = $row['field_id'];
	$fields[$row[field_id]]['field_type'] = $row['field_type'];

}
// Essential fields, always exists.



$fields['ad_id']['field_id'] = 'ad_id';
$fields['order_id']['field_id'] = 'order_id';
$fields['banner_id']['field_id'] = 'banner_id';
$fields['user_id']['field_id'] = 'user_id';
$fields['ad_date']['field_id'] = 'ad_date';



$sql = " show columns from ads ";
$result = mysql_query($sql) or die (mysql_error());
while ($row = mysql_fetch_row($result)) {
	$columns[$row[0]] = $row[0];


}

//print_r ($columns);
//echo "<hr>";
//print_r ($fields);

/*
 * Rules:
 * If exists in both, do nothing
 * If exists in form but not table, add to table
 * if NOT exists form, but is in table, remove from table
 */

$i=0;
foreach ($fields as $key=>$val) {

	if ($change =='') {
		$sql = "ALTER TABLE `ads` ";
	}

	# If exists in both, do nothing
	if (($columns[$key] != '') && 
		($fields[$key]['field_id'] != '')) { // do nothing

	}
	# If exists in form but not table, add to table
	if (($columns[$key] == '') && 
		($fields[$key]['field_id'] != '')) { // ADD to table
		if ($i>0) {$sql .= ", ";}
		$sql .= add_field ($key, $fields[$key]['field_type']);
		$change = 'Y';
	}

	$i++;

}

##
foreach ($columns as $key=>$val) {

	if ($change =='') {
		$sql = "ALTER TABLE `ads` ";
	}

	# If exists in both, do nothing
	if (($columns[$key] != '') && 
		($fields[$key]['field_id'] != '')) { // do nothing

	}
	
	# if NOT exists form, but is in table, 
	if (($columns[$key] != '') && 
		($fields[$key]['field_id'] == '')) { // REMOVE from table
		if ($i>0) {$sql .= ", ";}
		$sql .= remove_field($key);
		$change = 'Y';
	}

	$i++;

}

if ($change == 'Y') {
	
	mysql_query ($sql) or die (mysql_error());

	echo "Database Structure Updated.";
	if ((CACHE_ENABLED=='YES')) {
		$CACHE_ENABLED = 'NO';
		if (!function_exists('generate_form_cache')) {
			include ('../include/codegen_functions.php');
		}
		generate_form_cache(1);
	
		$CACHE_ENABLED='YES';
	}

} else {
	//echo "No Changes need to be made.";
}



function add_field ($field_id, $field_type) {
	
	return " ADD `$field_id` ".get_definition($field_type)." ";

}

function remove_field($field_id) {
	return " DROP  `$field_id` ";

}



?>
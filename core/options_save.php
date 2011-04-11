<?php
/*---------------------------------------------------------------------------------------------
	Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['options_meta_box'] == 'true')
{
	// set table name
	global $wpdb;
	$table_name = $wpdb->prefix.'acf_options';
	
	
	// turn inputs into database friendly data
	$options = $_POST['acf']['options'];
	
	
	// remove all old fields from the database
	$wpdb->query("DELETE FROM $table_name WHERE acf_id = '$post_id' AND type = 'option'");
	
	
	// loop through and save
	if($options)
	{
		foreach($options as $key => $value)
		{
			// already array, serialize the array for database save
			$value = serialize($value);
			
			
			//save todatabase
			$new_id = $wpdb->insert($table_name, array(
				'acf_id'	=>	$post_id,
				'name'		=>	$key,
				'value'		=>	$value,
				'type'		=>	'option'
			));
		}
	}
	
	
}

?>
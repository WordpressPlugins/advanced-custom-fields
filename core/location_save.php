<?php
/*---------------------------------------------------------------------------------------------
	Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['location_meta_box'] == 'true')
{
	// set table name
	global $wpdb;
	$table_name = $wpdb->prefix.'acf_options';
	
	
	// remove all old fields from the database
	$wpdb->query("DELETE FROM $table_name WHERE acf_id = '$post_id' AND type = 'location'");
	

	// turn inputs into database friendly data
	$locations = $_POST['acf']['location'];
	if($locations)
	{
		foreach($locations as $key => $value)
		{
			if(empty($value))
			{
				continue;
			}
			
			
			// if not an array (most inputs are strings), convert into an array
			if($key == 'post_types')
			{
				// already array, serialize the array for database save
				$value = serialize($value);
			}
			elseif($key == 'ignore_other_acfs')
			{
				// do nothing
			}
			else
			{
				// must be a string, lets explode it!
				$value = str_replace(', ',',',$value);
				$value = explode(',',$value);
				
				// serialize the array for database save
				$value = serialize($value);
			}
			
			// location can now be saved
			$new_id = $wpdb->insert($table_name, array(
				'acf_id'	=>	$post_id,
				'name'		=>	$key,
				'value'		=>	$value,
				'type'		=>	'location'
			));
			
			//echo $post_id.' : '.$key.': '.' : '.$value;
			
		}
	}
}

?>
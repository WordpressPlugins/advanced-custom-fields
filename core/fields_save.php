<?php
/*---------------------------------------------------------------------------------------------
	Save Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['fields_meta_box'] == 'true')
{
	// set table name
	global $wpdb;
	$table_name = $wpdb->prefix.'acf_fields';
	
	
	// remove all old fields from the database
	$wpdb->query("DELETE FROM $table_name WHERE post_id = '$post_id'");
	
	
	// loop through fields and save them
	$i = 0;
	foreach($_POST['acf']['fields'] as $field)
	{

		
		// format options if needed
		if(method_exists($this->fields[$field['type']], 'format_options'))
		{
			$field['options'] = $this->fields[$field['type']]->format_options($field['options']);
		}
		
		
		// create data
		$data = array(
			'order_no' 	=> 	$i,
			'post_id'	=>	$post_id,
			'label'		=>	$field['label'],
			'name'		=>	$field['name'],
			'type'		=>	$field['type'],
			'options'	=>	serialize($field['options']),
			
		);
		
		
		// if there is an id, this field already exists, so save it in the same ID spot
		if($field['id'])
		{
			$data['id']	= $field['id'];
		}
		
		
		// save field as row in database
		$new_id = $wpdb->insert($table_name, $data);
		
		
		// increase order_no
		$i++;
	}
}

?>
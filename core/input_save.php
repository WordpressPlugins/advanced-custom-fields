<?php
/*---------------------------------------------------------------------------------------------
	Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['input_meta_box'] == 'true')
{

    // If acf was not posted, don't go any further
    if(!isset($_POST['acf']))
    {
    	return true;
    }
    
    
    // save which ACF's were here: for use in the api
    add_post_meta($post_id, '_acf_id', $_POST['acf_id']);
    
    
    // set table name
	global $wpdb;
	$table_name = $wpdb->prefix.'acf_values';
	
	// remove all old values from the database
	$wpdb->query("DELETE FROM $table_name WHERE post_id = '$post_id'");
		
    foreach($_POST['acf'] as $field)
    {	
    	if(method_exists($this->fields[$field['field_type']], 'save_input'))
		{
			$this->fields[$field['field_type']]->save_input($post_id, $field);
		}
		else
		{
			//$field = apply_filters('wp_insert_post_data', $field);
			$field = stripslashes_deep( $field );
			
			$data = array(
				'post_id'	=>	$post_id,
				'field_id'	=>	$field['field_id'],
				'value'		=>	$field['value']
			);
			
			// if there is an id, this value already exists, so save it in the same ID spot
			if($field['row_id'])
			{
				$data['id']	= $field['row_id'];
			}
			
			
			// insert new data
			$new_id = $wpdb->insert($table_name, $data);
		}
    	
		
    }
  
	
}

?>
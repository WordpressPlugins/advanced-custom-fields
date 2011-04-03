<?php
/*---------------------------------------------------------------------------------------------
	Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['input_meta_box'] == 'true')
{
	// save acf id's
	add_post_meta($post_id, '_acf_id', $_POST['acf']['id']);
	
	// get field id's
	$acf_id = explode(',',$_POST['acf']['id']);
    $fields = array();
    
    if(empty($acf_id)){return null;}
    foreach($acf_id as $id)
	{
		$this_fields = $this->get_fields($id);
		if(empty($this_fields)){return null;}
		foreach($this_fields as $this_field)
		{
			$fields[] = $this_field;
		}
	}
	
	// now we have this page's fields, loop through them and save
	foreach($fields as $field)
	{
		// if this field has not been submitted, don't save, just continue to next loop
		/*if(!isset($_POST['acf'][$field['name']]))
		{
			echo $field['name'] . ' was not set';
			continue;
		}*/
		
		$options = array(
			'post_id'		=>	$post_id,
			'field_name'	=>	$field['name'],
			'field_value'	=>	$_POST['acf'][$field['name']],
			'field_type'	=>	$field['type'],
		);
		
		$this->save_field($options);
	}
	
}

?>
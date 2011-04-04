<?php 
/*---------------------------------------------------------------------------------------------
 * api.php
 *
 * @version 1.0.6
 ---------------------------------------------------------------------------------------------*/
 
/*---------------------------------------------------------------------------------------------
 * acf_object
 *
 * @author Elliot Condon
 * @since 1.0.0
 * 
 ---------------------------------------------------------------------------------------------*/
class acf_object
{
    function acf_object($variables)
    {
    	foreach($variables as $key => $value)
    	{
    		$this->$key = $value;
    	}
    }
    
}

/*---------------------------------------------------------------------------------------------
 * get_acf
 *
 * @author Elliot Condon
 * @since 1.0.0
 * 
 ---------------------------------------------------------------------------------------------*/
function get_acf($post_id = false)
{
	global $acf;
	global $wpdb;
	global $post;
	
	if(!$post_id)
	{
		$post_id = $post->ID;
	}
	
	$results = array();
    $acf_id = explode(',',get_post_meta($post_id, '_acf_id', true));
    
    $fields = array();
    
    // checkpoint
    
    if(empty($acf_id)){return null;}
    
    foreach($acf_id as $id)
	{
		$this_fields = $acf->get_fields($id);
		if(empty($this_fields)){return null;}
		foreach($this_fields as $this_field)
		{
			$fields[] = $this_field;
		}
	}
	
	// checkpoint
	if(empty($fields)){return null;}

	$variables = array();
	
	foreach($fields as $field)
	{
		// get value
		$field['value'] = get_post_meta($post_id, '_acf_'.$field['name'], true);
		
		// if field has a format function, format the value
		if($acf->fields[$field['type']]->has_format_value())
		{
			$field['value'] = $acf->fields[$field['type']]->format_value($field['value']);
		}
		
		// add name + value to variables array
		$variables[$field['name']] = $field['value'];
		
	}
	
	return new acf_object($variables);
	
	  
}

// get fields
function get_fields()
{
	return get_acf();
}

// get field
function get_field($field_name)
{
	global $acf_fields;
	global $post;
	
	if(empty($acf_fields))
	{
		$acf_fields = array();
		
		//echo 'acf_fields is empty';
		$acf_fields[$post->ID] = get_acf();
	}
	
	return $acf_fields[$post->ID]->$field_name;
}

// the field
function the_field($field_name)
{
	echo get_field($field_name);
}



?>
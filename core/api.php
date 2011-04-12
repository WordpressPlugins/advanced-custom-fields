<?php 
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
    		// field may exist but field name may be blank!!!
    		if($key)
    		{
    			$this->$key = $value;
    		}
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
	// get global vars
	global $acf;
	global $post;
	
	
	// create blank arrays
	$fields = array();
	$variables = array();
	
	
	// if no ID was passed through, just use the $post->ID
	if(!$post_id)
	{
		$post_id = $post->ID;
	}
	

	// get ID's for this post
    $acf_id = explode(',',get_post_meta($post_id, '_acf_id', true));
    
    
    // checkpoint: If no id's exist for this page, get out of here!
    if(empty($acf_id)){return null;}
	
	
	// loop through ID's
    foreach($acf_id as $id)
	{
		$this_fields = $acf->get_fields($id);
		if(empty($this_fields)){return null;}
		
		foreach($this_fields as $this_field)
		{
			$fields[] = $this_field;
		}
	}

	
	// checkpoint: If no fields, get out of here!
	if(empty($fields)){return null;}
	
	
	foreach($fields as $field)
	{
		// get value
		$field->value = $acf->load_value_for_api($post_id, $field);
		
		
		// add this field: name => value
		$variables[$field->name] = $field->value;
		
	}
	
	
	// create a new obejct and give in variables
	$object = new stdClass();
	
	foreach($variables as $key => $value)
	{
		$object->$key = $value;
	}
	
	
	// return the object
	return $object;
	
	  
}


// get fields
function get_fields($post_id = false)
{
	return get_acf($post_id);
}


// get field
function get_field($field_name, $post_id = false)
{
	global $acf_fields;
	global $post;
	
	if(!$post_id)
	{
		$post_id = $post->ID;
	}
	
	//echo 'field name: '.$field_name.', post id: '.$post_id;
	
	if(empty($acf_fields))
	{
		$acf_fields = array();
	}
	if(empty($acf_fields[$post_id]))
	{
		$acf_fields[$post_id] = get_acf($post_id);
	}
	
	return $acf_fields[$post_id]->$field_name;
}


// the field
function the_field($field_name, $post_id = false)
{
	//echo 'field name: '.$field_name.', post id: '.$post_id;
	echo get_field($field_name, $post_id);
}

?>
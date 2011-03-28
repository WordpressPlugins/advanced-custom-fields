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
function get_acf()
{
	global $acf;
	global $wpdb;
	global $post;
	
	if(!$post_id)
	{
		$post_id = $post->ID;
	}
	
	$results = array();
    $acf_id = get_post_meta($post_id, '_acf_id', true);
    
    $fields = $acf->get_fields($acf_id);
	
	if($fields)
	{
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
	else
	{
		return null;
	}    
}


?>
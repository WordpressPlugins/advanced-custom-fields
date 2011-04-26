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
		if (empty($key))
		{
			continue;
		}
		
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

/*---------------------------------------------------------------------------------------------
 * ACF_WP_Query
 *
 * @author Elliot Condon
 * @since 1.1.3
 * 
 ---------------------------------------------------------------------------------------------*/
class ACF_WP_Query extends WP_Query 
{
	var $orderby_field;
	var $order;
	var $orderby_type;
	
	function __construct($args=array())
	{
		// set default variabls
		$this->orderby_field = '';
		$this->order = 'ASC';
		$this->orderby_type = 'string';
		
		
		// set order
		if(!empty($args['order']))
		{
			$this->order = $args['order'];
		}
		
		
		// set value type
		if(!empty($args['orderby_type']))
		{
			$this->orderby_type = $args['orderby_type'];
		}
		
		
		if(!empty($args['orderby_field']))
		{
			$this->orderby_field = $args['orderby_field'];
			
			add_filter('posts_join', array($this, 'posts_join'));
			add_filter('posts_where', array($this, 'posts_where'));
			add_filter('posts_orderby', array($this, 'posts_orderby'));
		}
		
		parent::query($args);
	}
	
	function posts_join($join)
	{
		$join .= "LEFT JOIN wp_acf_values v ON v.post_id=wp_posts.ID
		LEFT JOIN wp_acf_fields f ON f.id=v.field_id";
			
		return $join;
	}
	
	function posts_where($where)
	{
		$where .= "AND f.name = '".$this->orderby_field."'";
	  	return $where;
	}
	
	function posts_orderby($orderby)
	{
	
		if($this->orderby_type == 'int')
		{
			$orderby = "ABS(v.value) ".$this->order;
		}
		else
		{
			$orderby = "v.value ".$this->order;
		}
		

		return $orderby;
	}
}

?>
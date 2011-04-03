<?php

class Text
{
	var $name;
	var $title;
	
	function Text()
	{
		$this->name = 'text';
		$this->title = 'Text';
	}
	
	function html($options)
	{
		echo '<input type="text" value="'.$options['value'].'" id="'.$options['id'].'" class="'.$options['class'].'" name="'.$options['name'].'" />';
	}
	
	function has_options()
	{
		return false;
	}
	
	function has_format_value()
	{
		return false;
	}
	
	function save_field($post_id, $field_name, $field_value)
	{
		// this is a normal text save
		add_post_meta($post_id, '_acf_'.$field_name, $field_value);
	}
	
}

?>
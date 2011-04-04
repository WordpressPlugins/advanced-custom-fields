<?php

class Textarea
{
	var $name;
	var $title;
	
	function Textarea()
	{
		$this->name = 'textarea';
		$this->title = 'Text Area';
	}
	
	function html($options)
	{
		echo '<textarea id="'.$options['id'].'" rows="4" class="'.$options['class'].'" name="'.$options['name'].'" >'.$options['value'].'</textarea>';
	}
	
	function has_options()
	{
		return false;
	}
	
	function has_format_value()
	{
		return true;
	}
	
	function format_value($value)
	{
		$value = nl2br($value);
		
		return $value;
	}
	
	function save_field($post_id, $field_name, $field_value)
	{
		// this is a normal text save
		add_post_meta($post_id, '_acf_'.$field_name, $field_value);
	}
}

?>
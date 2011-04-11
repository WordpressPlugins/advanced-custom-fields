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
	
	function html($field)
	{
		// remove unwanted <br /> tags
		$field->value = str_replace('<br />','',$field->value);
		echo '<textarea id="'.$field->input_id.'" rows="4" class="'.$field->input_class.'" name="'.$field->input_name.'" >'.$field->value.'</textarea>';
	}
	
	
	function format_value($value)
	{
		$value = nl2br($value);
		
		return $value;
	}
}

?>
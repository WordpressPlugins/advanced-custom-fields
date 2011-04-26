<?php

class Text
{
	var $name;
	var $title;
	
	function Text()
	{
		$this->name = 'text';
		$this->title = __("Text",'acf');
	}
	
	function html($field)
	{
		echo '<input type="text" value="'.$field->value.'" id="'.$field->input_id.'" class="'.$field->input_class.'" name="'.$field->input_name.'" />';
	}
	
	function format_value_for_input($value)
	{		
		$value = htmlspecialchars($value, ENT_QUOTES);
		return $value;
	}
	
}

?>
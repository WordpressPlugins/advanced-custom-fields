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
	
	function html($field)
	{
		echo '<input type="text" value="'.$field->value.'" id="'.$field->input_id.'" class="'.$field->input_class.'" name="'.$field->input_name.'" />';
	}

	
}

?>
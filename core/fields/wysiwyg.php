<?php

class Wysiwyg
{
	var $name;
	var $title;
	
	function Wysiwyg()
	{
		$this->name = 'wysiwyg';
		$this->title = 'Wysiwyg Editor';
	}
	
	function html($field)
	{
		echo '<div class="acf_wysiwyg"><textarea name="'.$field->input_name.'" >';
		echo wp_richedit_pre($field->value);
		echo '</textarea></div>';
	}
	
	function format_value($value)
	{
		$value = apply_filters('the_content',$value); 
		return $value;
	}
}

?>
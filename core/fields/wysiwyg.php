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
	
	function html($options)
	{
		echo '<div class="acf_wysiwyg"><textarea name="'.$options['name'].'" >';
		echo wp_richedit_pre($options['value']);
		echo '</textarea></div>';
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
		$value = apply_filters('the_content',$value); 
		
		return $value;
	}
}

?>
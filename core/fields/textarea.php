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
}

?>
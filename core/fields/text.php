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
	
}

?>
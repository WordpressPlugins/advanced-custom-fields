<?php

class File
{
	var $name;
	var $title;
	var $plugin_dir;
	
	function File($plugin_dir)
	{
		$this->name = 'file';
		$this->title = 'File';
		$this->plugin_dir = $plugin_dir;
	}
	
	function html($options)
	{
		echo '<div class="acf_file_uploader">';
		
		if($options['value'] != '')
		{
			echo '<a href="#" class="remove_file"></a>';
			echo '<span>'.$options['value'].'</span>';
			echo '<input type="hidden" name="'.$options['name'].'" value="'.$options['value'].'" />';
			echo '<iframe class="hide" src="'.$this->plugin_dir.'/core/upload.php"></iframe>';
		}
		else
		{
			echo '<a href="#" class="remove_file hide"></a>';
			echo '<input type="hidden" name="'.$options['name'].'" value="'.$options['value'].'" />';
			echo '<iframe src="'.$this->plugin_dir.'/core/upload.php"></iframe>';
		}
		
		echo '</div>';

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
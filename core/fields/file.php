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
	
	function html($field)
	{
		echo '<div class="acf_file_uploader">';
		
		if($field->value != '')
		{
			echo '<a href="#" class="remove_file"></a>';
			echo '<span>'.$field->value.'</span>';
			echo '<input type="hidden" name="'.$field->input_name.'" value="'.$field->value.'" />';
			echo '<iframe class="hide" src="'.$this->plugin_dir.'/core/upload.php"></iframe>';
		}
		else
		{
			echo '<a href="#" class="remove_file hide"></a>';
			echo '<input type="hidden" name="'.$field->input_name.'" value="'.$field->value.'" />';
			echo '<iframe src="'.$this->plugin_dir.'/core/upload.php"></iframe>';
		}
		
		echo '</div>';

	}

	
}

?>
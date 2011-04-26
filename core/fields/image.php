<?php

class Image
{
	var $name;
	var $title;
	var $plugin_dir;
	
	function Image($plugin_dir)
	{
		$this->name = 'image';
		$this->title = 'Image';
		$this->plugin_dir = $plugin_dir;
	}
	
	function html($field)
	{
		echo '<div class="acf_image_uploader">';
		
		if($field->value != '')
		{
			echo '<a href="#" class="remove_image"></a>';
			echo '<img src="'.$field->value.'"/>';
			echo '<input type="hidden" name="'.$field->input_name.'" value="'.$field->value.'" />';
			echo '<iframe class="hide" src="'.$this->plugin_dir.'/core/upload.php"></iframe>';
		}
		else
		{
			echo '<a href="#" class="remove_image hide"></a>';
			echo '<input type="hidden" name="'.$field->input_name.'" value="'.$field->value.'" />';
			echo '<iframe src="'.$this->plugin_dir.'/core/upload.php"></iframe>';
		}
		
		echo '</div>';

	}

	
}

?>
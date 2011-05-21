<?php

class Date_picker
{
	var $name;
	var $title;
	var $plugin_dir;
	
	function Date_picker($plugin_dir)
	{
		$this->name = 'date_picker';
		$this->title = __('Date Picker','acf');
		$this->plugin_dir = $plugin_dir;
	}
	
	function html($field)
	{
		echo '<link rel="stylesheet" type="text/css" href="'.$this->plugin_dir.'/core/fields/date_picker/style.date_picker.css" />';
		echo '<script type="text/javascript" src="'.$this->plugin_dir.'/core/fields/date_picker/jquery.ui.datepicker.js" ></script>';
		echo '<input type="hidden" value="'.$field->options['date_format'].'" name="date_format" />';
		echo '<input type="text" value="'.$field->value.'" id="'.$field->input_id.'" class="acf_datepicker" name="'.$field->input_name.'" />';

	}
	
	function options_html($key, $options)
	{
		?>
		<table class="acf_input">
		<tr>
			<td class="label">
				<label for=""><?php _e("Date format",'acf'); ?></label>
			</td>
			<td>
				<input type="text" name="acf[fields][<?php echo $key; ?>][options][date_format]" id="" value="<?php echo $options['date_format']; ?>" />
				<p class="description"><?php _e("eg. dd/mm/yy. read more about",'acf'); ?> <a href="http://docs.jquery.com/UI/Datepicker/formatDate">formatDate</a></p>
			</td>
		</tr>
		</table>
		<?php
	}
		
	
	
}

?>
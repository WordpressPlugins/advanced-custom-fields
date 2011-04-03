<?php

class Date_picker
{
	var $name;
	var $title;
	var $plugin_dir;
	
	function Date_picker($plugin_dir)
	{
		$this->name = 'date_picker';
		$this->title = 'Date Picker';
		$this->plugin_dir = $plugin_dir;
	}
	
	function html($options)
	{
		echo '<link rel="stylesheet" type="text/css" href="'.$this->plugin_dir.'/core/fields/date_picker/style.date_picker.css" />';
		echo '<script type="text/javascript" src="'.$this->plugin_dir.'/core/fields/date_picker/jquery.ui.datepicker.js" ></script>';
		echo '<input type="hidden" value="'.$options['options']['date_format'].'" name="date_format" />';
		echo '<input type="text" value="'.$options['value'].'" id="'.$options['id'].'" class="acf_datepicker" name="'.$options['name'].'" />';

	}
	
	function has_options()
	{
		return true;
	}
	
	function options($key, $options)
	{
		?>
		<table class="acf_input">
		<tr>
			<td class="label">
				<label for="">Date format</label>
			</td>
			<td>
				<input type="text" name="acf[fields][<?php echo $key; ?>][options][date_format]" id="" value="<?php echo $options['date_format']; ?>" />
				<p class="description">eg. dd/mm/yy. read more about <a href="http://docs.jquery.com/UI/Datepicker/formatDate">formatDate</a></p>
			</td>
		</tr>
		</table>
		<?php
	}
		
	function has_format_value()
	{
		return false;
	}

	
	function save_field($post_id, $field_name, $field_value)
	{
		// this is a normal text save
		add_post_meta($post_id, '_acf_'.$field_name, $field_value);
	}
	
}

?>
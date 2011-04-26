<?php

class Checkbox
{
	var $name;
	var $title;
	
	function Checkbox()
	{
		$this->name = 'checkbox';
		$this->title = __('Checkbox','acf');
	}
	
	function html($field)
	{
		if(empty($field->value))
		{
			$field->value = array();
		}
		
		echo '<ul class="checkbox_list '.$field->input_class.'">';
		// loop through values and add them as options
		
		$name_extra = '[]';
		if(count($field->options['choices']) <= 1)
		{
			$name_extra = '';
		}
			
		foreach($field->options['choices'] as $key => $value)
		{
			$selected = '';
			if(in_array($key, $field->value))
			{
				$selected = 'checked="yes"';
			}
			echo '<li><input type="checkbox" class="'.$field->input_class.'" name="'.$field->input_name.$name_extra.'" value="'.$key.'" '.$selected.' />'.$value.'</li>';
		}
		echo '</ul>';

	}


	/*---------------------------------------------------------------------------------------------
	 * Options HTML
	 * - called from fields_meta_box.php
	 * - displays options in html format
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function options_html($key, $options)
	{
		// implode checkboxes so they work in a textarea
		if(!empty($options['choices']) && is_array($options['choices']))
		{		
			foreach($options['choices'] as $choice_key => $choice_val)
			{
				$options['choices'][$choice_key] = $choice_key.' : '.$choice_val;
			}
			$options['choices'] = implode("\n", $options['choices']);
		}
		
		?>

		<table class="acf_input">
		<tr>
			<td class="label">
				<label for=""><?php _e("Choices",'acf');_e("",'acf') ?></label>
			</td>
			<td>
				<textarea rows="5" name="acf[fields][<?php echo $key; ?>][options][choices]" id=""><?php echo $options['choices']; ?></textarea>
				<p class="description"><?php _e("Enter your choices one per line. eg:<br />
				option_1 : Option 1<br />
				option_3 : Option 2<br />
				option_3 : Option 3",'acf'); ?></p>
			</td>
		</tr>
		</table>
	
		<?php
	}


	/*---------------------------------------------------------------------------------------------
	 * save input
	 * - called from fields_save.php
	 * - saves input data
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function save_input($post_id, $field)
	{
		// set table name
		global $wpdb;
		$table_name = $wpdb->prefix.'acf_values';
		
		
		// if select is a multiple, you need to save it as an array!
		if(is_array($field['value']))
		{
			$field['value'] = serialize($field['value']);
		}
		
		
		// insert new data
		$new_id = $wpdb->insert($table_name, array(
			'post_id'	=>	$post_id,
			'field_id'	=>	$field['field_id'],
			'value'		=>	$field['value']
		));
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Format Options
	 * - this is called from save_field.php, this function formats the options into a savable format
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function format_options($options)
	{	
		// if no choices, dont do anything
		if($options['choices'] == '')
		{
			return $options;
		}
		
		
		// explode choices from each line
		if(strpos($options['choices'], "\n") !== false)
		{
			// found multiple lines, explode it
			$choices = explode("\n", $options['choices']);
		}
		else
		{
			// no multiple lines! 
			$choices = array($options['choices']);
		}
		
		
		
		$new_choices = array();
		foreach($choices as $choice)
		{
			if(strpos($choice, ' : ') !== false)
			{

				$choice = explode(' : ', $choice);
				$new_choices[trim($choice[0])] = trim($choice[1]);
			}
			else
			{
				$new_choices[trim($choice)] = trim($choice);
			}
		}
		
		
		// return array containing all choices
		$options['choices'] = $new_choices;
		
		return $options;
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Format Value
	 * - this is called from api.php
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function format_value_for_api($value)
	{
		if(is_array(unserialize($value)))
		{
			return(unserialize($value));
		}
	}
	
	/*---------------------------------------------------------------------------------------------
	 * Format Value for input
	 * - this is called from acf.php
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function format_value_for_input($value)
	{
		return $this->format_value_for_api($value);
	}
}

?>
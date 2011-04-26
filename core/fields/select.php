<?php

class Select
{
	var $name;
	var $title;
	var $parent;
	
	function Select($parent)
	{
		$this->name = 'select';
		$this->title = 'Select';
		$this->parent = $parent;
	}
	
	function html($field)
	{
		if($field->options['multiple'] == '1')
		{
			$name_extra = '[]';
			if(count($field->options['choices']) <= 1)
			{
				$name_extra = '';
			}
			echo '<select id="'.$field->input_id.'" class="'.$field->input_class.'" name="'.$field->input_name.$name_extra.'" multiple="multiple" size="5" >';
		}
		else
		{
			echo '<select id="'.$field->input_id.'" class="'.$field->input_class.'" name="'.$field->input_name.'" >';	
			// add top option
			echo '<option value="null">- Select Option -</option>';
		}
		
		
		
		
		
		// loop through values and add them as options
		foreach($field->options['choices'] as $key => $value)
		{
			$selected = '';
			if(is_array($field->value))
			{
				// 2. If the value is an array (multiple select), loop through values and check if it is selected
				if(in_array($key, $field->value))
				{
					$selected = 'selected="selected"';
				}
			}
			else
			{
				// 3. this is not a multiple select, just check normaly
				if($key == $field->value)
				{
					$selected = 'selected="selected"';
				}
			}	
			
			
			echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}

		echo '</select>';
	}
	
	function has_options()
	{
		return true;
	}
	
	function options_html($key, $options)
	{
		// implode selects so they work in a textarea
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
				<label for="">Choices</label>
			</td>
			<td>
				<textarea rows="5" name="acf[fields][<?php echo $key; ?>][options][choices]" id=""><?php echo $options['choices']; ?></textarea>
				<p class="description">Enter your choices one per line. eg:<br />
				option_1 : Option 1<br />
				option_3 : Option 2<br />
				option_3 : Option 3</p>
			</td>
		</tr>
		<tr>
			<td class="label">
				<label>Multiple?</label>
			</td>
			<td>
				<?php 
					$temp_field = new stdClass();	
					$temp_field->type = 'true_false';
					$temp_field->input_name = 'acf[fields]['.$key.'][options][multiple]';
					$temp_field->input_class = '';
					$temp_field->input_id = 'acf[fields]['.$key.'][options][multiple]';
					$temp_field->value = $options['multiple'];
					$temp_field->options = array('message' => 'Select multiple values');
					$this->parent->create_field($temp_field); 
				?>
			</td>
		</tr>
		</table>
		<?php
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Save Input
	 * - this is called from save_input.php, this function saves the field's value(s)
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
		return $this->format_value_for_input($value);
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Format Value for input
	 * - this is called from api.php
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function format_value_for_input($value)
	{
		if(is_array(unserialize($value)))
		{
			return(unserialize($value));
		}
		else
		{
			return $value;
		}
	}
	
	
	
}

?>
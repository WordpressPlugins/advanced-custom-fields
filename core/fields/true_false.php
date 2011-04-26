<?php

class True_false
{
	var $name;
	var $title;
	
	function True_false()
	{
		$this->name = 'true_false';
		$this->title = __("True / False",'acf');
	}
	
	function html($field)
	{
		// set default message
		if(empty($field->options['message']))
		{
			$field->options['message'] = __("True",'acf');
		}
		
		// set choices
		$field->options['choices'] = array(
			'1' =>	$field->options['message']
		);
		
		// echo html
		echo '<ul class="checkbox_list '.$field->input_class.'">';
		
		foreach($field->options['choices'] as $key => $value)
		{
			$selected = '';
			if($key == $field->value)
			{
				$selected = 'checked="yes"';
			}
			echo '<li><input type="checkbox" class="'.$field->input_class.'" name="'.$field->input_name.'" value="'.$key.'" '.$selected.' />'.$value.'</li>';
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
		?>
		<table class="acf_input">
		<tr>
			<td class="label">
				<label for="acf[fields][<?php echo $key; ?>][options][message]"><?php _e("Message",'acf'); ?></label>
			</td>
			<td>
				<input type="text" name="acf[fields][<?php echo $key; ?>][options][message]" id="acf[fields][<?php echo $key; ?>][options][message]" value="<?php echo $options['message']; ?>" />
				<p class="description"><?php _e("eg. Show extra content",'acf'); ?></a></p>
			</td>
		</tr>
		</table>
		<?php
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
		if($value == '1')
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	
}

?>
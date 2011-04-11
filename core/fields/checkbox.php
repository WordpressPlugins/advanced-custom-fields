<?php

class Checkbox
{
	var $name;
	var $title;
	
	function Checkbox()
	{
		$this->name = 'checkbox';
		$this->title = 'Checkbox';
	}
	
	function html($options)
	{
		if(empty($options['value']))
		{
			$options['value'] = array();
		}
		else
		{
			$options['value'] = str_replace(', ',',',$options['value']);
			$options['value'] = explode(',',$options['value']);
		}
		
		echo '<ul class="checkbox_list '.$options['class'].'">';
		// loop through values and add them as options
		
		$name_extra = '[]';
		if(count($options['options']['choices']) <= 1)
		{
			$name_extra = '';
		}
			
		foreach($options['options']['choices'] as $key => $value)
		{
			$selected = '';
			if(in_array($key, $options['value']))
			{
				$selected = 'checked="yes"';
			}
			echo '<li><input type="checkbox" class="'.$options['class'].'" name="'.$options['name'].$name_extra.'" value="'.$key.'" '.$selected.' />'.$value.'</li>';
		}
		echo '</ul>';

	}
	
	function has_options()
	{
		return true;
	}
	
	function options($key, $options)
	{
		//if($options['choices'] == ''){$options['choices'] = "option 1\noption 2\noption 3";}
		?>

		<table class="acf_input">
		<tr>
			<td class="label">
				<label for="">Choices</label>
				<p>Enter your choices one per line. eg:<br />
				Option 1<br />
				Option 2 <br />
				Option 3</p>
			</td>
			<td>
				<textarea rows="5" name="acf[fields][<?php echo $key; ?>][options][choices]" id=""><?php echo $options['choices']; ?></textarea>
			</td>
		</tr>
		</table>
	
		<?php
	}
	
	function has_format_value()
	{
		return true;
	}
	
	function format_value($value)
	{
		$value = str_replace(', ',',',$value);
		$value = explode(',',$value);
		
		if(!is_array($value))
		{
			$value = array($value);
		}
		
		return $value;
	}
	
	function save_field($post_id, $field_name, $field_value)
	{
		if(is_array($field_value))
		{
			$field_value = implode(',',$field_value);
		}
		add_post_meta($post_id, '_acf_'.$field_name, $field_value);
	}
	
}

?>
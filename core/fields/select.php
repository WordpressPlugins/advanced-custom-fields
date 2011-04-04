<?php

class Select
{
	var $name;
	var $title;
	
	function Select()
	{
		$this->name = 'select';
		$this->title = 'Select';
	}
	
	function html($options)
	{
		
		//$options['choices'] = explode("\n",$options['choices']);
		if($options['options']['multiple'] == 'true')
		{
			echo '<select id="'.$options['id'].'" class="'.$options['class'].'" name="'.$options['name'].'[]" multiple="multiple" size="5" >';
		}
		else
		{
			echo '<select id="'.$options['id'].'" class="'.$options['class'].'" name="'.$options['name'].'" >';	
			// add top option
			echo '<option value="null">- Select Option -</option>';
		}
		
		
		
		
		
		// loop through values and add them as options
		foreach($options['options']['choices'] as $key => $value)
		{
			$selected = '';
			if($options['options']['multiple'] == 'true' && empty($options['value']))
			{
				// 1. If it is multiple select & there are no values selected, make all options selected
				$selected = 'selected="selected"';
			}
			elseif(is_array($options['value']))
			{
				// 2. If the value is an array (multiple select), loop through values and check if it is selected
				if(in_array($key, $options['value']))
				{
					$selected = 'selected="selected"';
				}
			}
			else
			{
				// 3. this is not a multiple select, just check normaly
				if($key == $options['value'])
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
	
	function options($key, $options)
	{
		//if($options['choices'] == ''){$options['choices'] = "option 1\noption 2\noption 3";}
		?>
		<table class="acf_input">
		<tr>
			<td class="label">
				<label for="">Choices</label>
			</td>
			<td>
				<textarea rows="5" name="acf[fields][<?php echo $key; ?>][options][choices]" id=""><?php echo $options['choices']; ?></textarea>
				<p class="description">Enter your choices one per line. eg:<br />
				Option 1<br />
				Option 2 <br />
				Option 3</p>
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
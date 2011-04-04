<?php
	
	global $post;
	
	$acfs = $args['args']['acfs'];
	$acf_ids = array();
	
	$fields = array();
	
	foreach($acfs as $acf)
	{
		// get this acf's fields and add them to the global $fields
		$this_fields = $this->get_fields($acf->ID);
		foreach($this_fields as $this_field)
		{
			$fields[] = $this_field;
		}
		
		// add id to array (easy to explode it in a hidden input on line 68)
		$acf_ids[] = $acf->ID;
	}
	
	// get options from first (top level) acf
	$adv_options = $this->get_adv_options($acfs[0]->ID);

	// loop through multiple acfs
	/*$adv_options = array();
	foreach($acfs as $acf)
	{
		// get this acf's fields and add them to the global $fields
		$this_fields = $this->get_fields($acf->ID);
		foreach($this_fields as $this_field)
		{
			$fields[] = $this_field;
		}
		
		$this_options = $this->get_adv_options($acf->ID);
		foreach($this_options as $key => $this_option)
		{
			// if global options doesn't even have this key, just add the array
			if(empty($adv_options[$key]))
			{
				$adv_options[$key] = $this_option;
			}
			else
			{
				// the global array has the so it has an array here
				foreach($this_option as $key2 => $this_option_value)
				{
					// if the global options is blank at this key, add in the new key.
					if(empty($adv_options[$key][$key2]))
					{
						$adv_options[$key][$key2] = $this_option_value;
					}
				}
			}
			
			
		}
	}*/
	

?>

<input type="hidden" name="ei_noncename" id="ei_noncename" value="<?php echo wp_create_nonce('ei-n'); ?>" />

<input type="hidden" name="input_meta_box" value="true" />
<input type="hidden" name="acf[id]" value="<?php echo implode(',',$acf_ids); ?>" />

<?php if(!in_array('the_content',$adv_options['show_on_page'])): // hide the content quicker than jquery ?>
	<style type="text/css">
		#postdivrich {display: none;}
	</style>
<?php endif; ?>

<?php foreach($adv_options['show_on_page'] as $option): ?>
	
	<input type="hidden" name="show_<?php echo $option; ?>" value="true" />
<?php endforeach; ?>

<table class="acf_input" id="acf_input">
	<?php foreach($fields as $field): ?>
	<?php 
		// if they didn't select a type, skip this field
		if($field['type'] == 'null')
		{
			continue;
		}
		
		$field['value'] = get_post_meta($post->ID, '_acf_'.$field['name'], true);
		
		$field['id'] = 'acf['.$field['name'].']';
		$field['name'] = 'acf['.$field['name'].']';

		if($field['type'] == 'select' || $field['type'] == 'checkbox')
		{
			$array = array();
			foreach(explode("\n",$field['options']['choices']) as $choice)
			{
				$array[trim($choice)] = trim($choice);
			}
			$field['options']['choices'] = $array;
		}
		
		//print_r($field['options']['choices']);
	?>
	<tr>

		<td>
			<label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
			<?php $this->create_field($field); ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

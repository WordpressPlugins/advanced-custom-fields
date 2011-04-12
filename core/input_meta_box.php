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
	$adv_options = $this->get_acf_options($acfs[0]->ID);
	

?>


<input type="hidden" name="ei_noncename" id="ei_noncename" value="<?php echo wp_create_nonce('ei-n'); ?>" />
<input type="hidden" name="input_meta_box" value="true" />
<input type="hidden" name="acf_id" value="<?php echo implode(',',$acf_ids); ?>" />
<?php 


// hide the_content with style (faster than waiting for jquery to load)
if(!in_array('the_content',$adv_options->show_on_page)): ?>
	<style type="text/css">
		#postdivrich {display: none;}
	</style>
<?php endif; ?>


<?php foreach($adv_options->show_on_page as $option): ?>
	<input type="hidden" name="show_<?php echo $option; ?>" value="true" />
<?php endforeach; ?>

<table class="acf_input" id="acf_input">
	<?php $i = -1; ?>
	<?php foreach($fields as $field): $i++ ?>
	<?php 
		
		// if they didn't select a type, skip this field
		if($field->type == 'null')
		{
			continue;
		}
		
		
		
		// set value, id and name for field
		$field->value = $this->load_value_for_input($post->ID, $field);
		$field->input_id = 'acf['.$i.'][value]';
		$field->input_name = 'acf['.$i.'][value]';
		
	?>
	<tr>
		<td>
			<input type="hidden" name="acf[<?php echo $i; ?>][field_type]" value="<?php echo $field->type; ?>" />
			<input type="hidden" name="acf[<?php echo $i; ?>][field_id]" value="<?php echo $field->id; ?>" />
			<label for="<?php echo $field->input_id ?>"><?php echo $field->label ?></label>
			<?php $this->create_field($field); ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

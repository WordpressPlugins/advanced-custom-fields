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

	}
	
	// get options from first (top level) acf
	$adv_options = $this->get_acf_options($acfs[0]->ID);
	

?>


<input type="hidden" name="ei_noncename" id="ei_noncename" value="<?php echo wp_create_nonce('ei-n'); ?>" />
<input type="hidden" name="input_meta_box" value="true" />
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

<div class="acf_fields_input">
	<?php $i = -1; ?>
	<?php foreach($fields as $field): $i++ ?>
	<?php 
		
		// if they didn't select a type, skip this field
		if($field->type == 'null')
		{
			continue;
		}
		
	
		// set value, id and name for field
		$value = $this->load_value_for_input($post->ID, $field);

		$field->value = $value;
		$field->input_id = 'acf['.$i.'][value]';
		$field->input_name = 'acf['.$i.'][value]';
		$field->input_class = '';
		
	?>
	<div class="field">
		<?php //print_r($value); ?>
		<input type="hidden" name="acf[<?php echo $i; ?>][field_id]" value="<?php echo $field->id; ?>" />
		<input type="hidden" name="acf[<?php echo $i; ?>][field_type]" value="<?php echo $field->type; ?>" />
		
		<?php if($field->save_as_cf == 1): ?>
		<input type="hidden" name="acf[<?php echo $i; ?>][save_as_cf]" value="<?php echo $field->name; ?>" />
		<?php endif; ?>
		
		<label for="<?php echo $field->input_id ?>"><?php echo $field->label ?></label>
		<?php if($field->instructions): ?><p class="instructions"><?php echo $field->instructions; ?></p><?php endif; ?>
		<?php $this->create_field($field); ?>
		
	</div>
	<?php endforeach; ?>
</div>

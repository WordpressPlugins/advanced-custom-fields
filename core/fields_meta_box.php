<?php
	
	// get fields
	global $post;
	$fields = $this->get_fields($post->ID);
	
	
	// get name of all fields for use in field type drop down
	$fields_names = array();
	foreach($this->fields as $field)
	{
		$fields_names[$field->name] = $field->title;
	}
	
?>
<input type="hidden" name="fields_meta_box" value="true" />
<input type="hidden" name="total_fields" value="<?php echo count($fields); ?>" />
<input type="hidden" name="fields_limit" value="99" />

<input type="hidden" name="ei_noncename" id="ei_noncename" value="<?php echo wp_create_nonce('ei-n'); ?>" />

<div class="fields_heading">
	<table class="acf">
		<tr>
			<th class="order"><!-- Order --></th>
			<th class="label">Label<br /><span>Shown on the edit page (eg. Hero Image)</span></th>
			<th class="name">Name<br /><span>Used as variable name (eg. hero_image)</span></th>
			<th class="type">Field Type<br /><span>Type of field</span></th>
			<th class="blank"></th>
			<th class="remove"><!-- Remove --></th>
		</tr>
	</table>
</div>
<div class="fields">
	<?php foreach($fields as $key => $field): ?>
		<div class="field">
			
			<input type="hidden" name="acf[fields][<?php echo $key; ?>][id]'" value="<?php echo $field->id; ?>" />
			<?php $temp_field = new stdClass(); ?>

			<table class="acf">
				<tr>
					<td class="order"><?php echo ($key+1); ?></td>
					<td class="label">
						<?php 
							
							$temp_field->type = 'text';
							$temp_field->input_name = 'acf[fields]['.$key.'][label]';
							$temp_field->input_class = 'label';
							$temp_field->value = $field->label;
							
							$this->create_field($temp_field); 
						
						?>
					</td>
					<td class="name">
						<?php 
							
							$temp_field->type = 'text';
							$temp_field->input_name = 'acf[fields]['.$key.'][name]';
							$temp_field->input_class = 'name';
							$temp_field->value = $field->name;
							
							$this->create_field($temp_field); 
						
						?>
					</td>
					<td class="type">
						<?php 
							
							$temp_field->type = 'select';
							$temp_field->input_name = 'acf[fields]['.$key.'][type]';
							$temp_field->input_class = 'type';
							$temp_field->value = $field->type;
							$temp_field->options = array('choices' => $fields_names);
							
							$this->create_field($temp_field); 
						
						?>
					</td>
					<td class="blank"></td>
					<td class="remove"><a href="javascript:;" class="remove_field"></a></td>
				</tr>
			</table>
			
			<div class="field_options">
				<?php foreach($fields_names as $field_name => $field_title): ?>
					<?php if(method_exists($this->fields[$field_name], 'options_html')): ?>
						<div class="field_option" id="<?php echo $field_name; ?>">
							<?php $this->fields[$field_name]->options_html($key, $field->options); ?>
						</div>	
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		
		</div>
		<?php endforeach; ?>
</div>

<div class="table_footer">
	<div class="order_message"></div>
	<a href="javascript:;" id="add_field" class="button-primary">+ Add Field</a>
</div>
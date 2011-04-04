<?php
	
	// get fields
	global $post;
	$fields = $this->get_fields($post->ID);
	
	// if no fields (new acf), add blank field
	if(empty($fields))
	{
		$fields[] = array(
			'title'		=> 	'',
			'label'		=>	'',
			'type'		=>	'text',
			'options'	=>	array()
		);
	}
	
	// get name of all fields for use in field type
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
		
			<table class="acf">
				<tr>
					<td class="order"><?php echo ($key+1); ?></td>
					<td class="label">
						<?php $this->create_field(array(
							'type'	=>	'text',
							'name'	=>	'acf[fields]['.$key.'][label]',
							'value'	=>	$field['label'],
							'class'	=>	'label'
						)); ?>
					</td>
					<td class="name">
						<?php $this->create_field(array(
							'type'	=>	'text',
							'name'	=>	'acf[fields]['.$key.'][name]',
							'value'	=>	$field['name'],
							'class'	=>	'name'
						)); ?>
					</td>
					<td class="type">
						<?php $this->create_field(array(
							'type'				=>	'select',
							'name'				=>	'acf[fields]['.$key.'][type]',
							'value'				=>	$field['type'],
							'class'				=>	'type',
							'options'			=>	array('choices' => $fields_names)
						)); ?>
					</td>
					<td class="blank"></td>
					<td class="remove"><a href="javascript:;" class="remove_field"></a></td>
				</tr>
			</table>
			
			<div class="field_options">
				<?php foreach($fields_names as $field_name => $field_title): ?>
					<?php if($this->fields[$field_name]->has_options()): ?>
						<div class="field_option" id="<?php echo $field_name; ?>">
							<?php $this->fields[$field_name]->options($key, $field['options']); ?>
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
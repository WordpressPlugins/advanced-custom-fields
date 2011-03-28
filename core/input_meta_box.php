<?php
	
	global $post;
	
	$acf = $args['args']['acf'];
	
	$fields = $this->get_fields($acf->ID);
	$adv_options = $this->get_adv_options($acf->ID);
?>

<input type="hidden" name="ei_noncename" id="ei_noncename" value="<?php echo wp_create_nonce('ei-n'); ?>" />

<input type="hidden" name="input_meta_box" value="true" />
<input type="hidden" name="acf[id]" value="<?php echo $acf->ID; ?>" />

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

<?php
/*---------------------------------------------------------------------------------------------
	Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['fields_meta_box'] == 'true')
{
	$i = 0;

	foreach($_POST['acf']['fields'] as $field)
	{
		// add post meta
		add_post_meta($post_id, '_acf_field_'.$i.'_label', $field['label']);
		add_post_meta($post_id, '_acf_field_'.$i.'_name', $field['name']);
		add_post_meta($post_id, '_acf_field_'.$i.'_type', $field['type']);
		
		if(!empty($field['fields']))
		{
			$j = 0;
			
			foreach($field['fields'] as $repeater)
			{
				// add post meta
				add_post_meta($post_id, '_acf_field_'.$i.'_field_'.$j.'_label', $repeater['label']);
				add_post_meta($post_id, '_acf_field_'.$i.'_field_'.$j.'_name', $repeater['name']);
				add_post_meta($post_id, '_acf_field_'.$i.'_field_'.$j.'_type', $repeater['type']);
				
				$j++;
			}
		}
		
		add_post_meta($post_id, '_acf_field_'.$i.'_options', serialize($field['options']));

		// increase counter
		$i++;
	}
}

?>
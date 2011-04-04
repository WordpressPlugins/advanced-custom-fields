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
		
		//$options = array();
		//foreach($field['options'] as $option)
		//{
		//	if(!empty($option))
		//	{
		//		$options[] = $option;
		//	}
		//}
		add_post_meta($post_id, '_acf_field_'.$i.'_options', serialize($field['options']));
		
		//print_r(serialize($field['options']));
		//die;
		
		// increase counter
		$i++;
	}
}

?>
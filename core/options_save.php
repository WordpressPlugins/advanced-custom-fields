<?php
/*---------------------------------------------------------------------------------------------
	Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['options_meta_box'] == 'true')
{
	$options = $_POST['acf']['options'];
	
	if(is_array($options['show_on_page']))
	{
		$options['show_on_page'] = implode(', ',$options['show_on_page']);
	}
	
	
	// add post meta
	add_post_meta($post_id, '_acf_option_show_on_page', $options['show_on_page']);
	
}

?>
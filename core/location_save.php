<?php
/*---------------------------------------------------------------------------------------------
	Fields Meta Box
---------------------------------------------------------------------------------------------*/
if($_POST['location_meta_box'] == 'true')
{
	$location = $_POST['acf']['location'];
	
	// add post meta
	if(is_array($location['post_type']))
	{
		$location['post_type'] = implode(',',$location['post_type']);
	}
	
	add_post_meta($post_id, '_acf_location_post_type', $location['post_type']);
	add_post_meta($post_id, '_acf_location_page_slug', $location['page_slug']);
	add_post_meta($post_id, '_acf_location_post_id', $location['post_id']);
	add_post_meta($post_id, '_acf_location_page_template', $location['page_template']);
	add_post_meta($post_id, '_acf_location_parent_id', $location['parent_id']);
	
}

?>
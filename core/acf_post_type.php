<?php

$labels = array(
	'name' => __( 'Advanced&nbsp;Custom&nbsp;Fields', 'acf' ),
	'singular_name' => __( 'Advanced Custom Fields', 'acf' ),
	'search_items' =>  __( 'Search Advanced Custom Fields' , 'acf' ),
	'all_items' => __( 'All Advanced Custom Fields' , 'acf' ),
	'parent_item' => __( 'Parent Advanced Custom Fields' , 'acf' ),
	'parent_item_colon' => __( 'Parent Advanced Custom Fields:' , 'acf' ),
	'edit_item' => __( 'Edit Advanced Custom Fields' , 'acf' ),
	'update_item' => __( 'Update Advanced Custom Fields' , 'acf' ),
	'add_new_item' => __( 'Add New Advanced Custom Fields' , 'acf' ),
	'new_item_name' => __( 'New Advanced Custom Fields Name' , 'acf' ),
); 	

$supports = array(
	'title',
	'revisions',
	'custom-fields',
	'page-attributes'
);

register_post_type('acf', array(
	'labels' => $labels,
	'public' => false,
	'show_ui' => true,
	'_builtin' =>  false,
	'capability_type' => 'page',
	'hierarchical' => true,
	'rewrite' => array("slug" => "acf"),
	'query_var' => "acf",
	'supports' => $supports,
));


?>
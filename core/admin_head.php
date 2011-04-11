<?php

global $post;
		
// shows hidden custom fields
echo "<style type='text/css'>#postcustom .hidden { display: table-row; }</style>";

// add metabox, style and javascript to acf page
//if($_GET['post_type'] == 'acf')
//{
	// not edit screen
//}

$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];

if($currentFile == 'edit.php')
{

}
elseif(get_post_type($post) == 'acf')
{

	// Custom field page for ACF
	echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.fields.js" ></script>';
	
	echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.global.css" />';
	echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.fields.css" />';
	echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.location.css" />';
	echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.options.css" />';
	
	add_meta_box('acf_fields', 'Fields', array($this, '_fields_meta_box'), 'acf', 'normal', 'high');
	add_meta_box('acf_location', 'Assign to edit page</span><span class="description">- Specify exactly where you want your Advanced Custom Fields fields to appear', array($this, '_location_meta_box'), 'acf', 'normal', 'high');
	add_meta_box('acf_options', 'Advanced Options</span><span class="description">- Customise the edit page', array($this, '_options_meta_box'), 'acf', 'normal', 'high');
}
else
{
	// any other edit page
	$acfs = get_pages(array(
		'numberposts' 	=> 	-1,
		'post_type'		=>	'acf',
		'sort_column' 	=>	'menu_order',
	));
	
	// blank array to hold acfs
	$add_acf = array();
	
	if($acfs)
	{
		foreach($acfs as $acf)
		{
			$add_box = false;
			
			// get options of matrix
			$location = $this->get_acf_location($acf->ID);
			//print_r($location);
			
			// post type
			if($location['post_type'] != '')
			{
				$post_types = explode(',',str_replace(' ','',$location['post_type']));
				if(in_array(get_post_type($post), $post_types)) {$add_box = true; }
			}
			
			// page slug
			if($location['page_slug'] != '')
			{
				$page_slugs = explode(',',str_replace(' ','',$location['page_slug']));
				if(in_array($post->post_name, $page_slugs)) {$add_box = true; }
			}
			
			// post ID
			if($location['post_id'] != '')
			{
				$post_ids = explode(',',str_replace(' ','',$location['post_id']));
				if(in_array($post->ID, $post_ids)) {$add_box = true; }
			}
			
			// page template
			if($location['page_template'] != '')
			{
				$page_template = explode(',',str_replace(' ','',$location['page_template']));
				if(in_array(get_post_meta($post->ID,'_wp_page_template',true), $page_template)) {$add_box = true;}
			}
			
			// parent id
			if($location['parent_id'] != '')
			{
				$parent_ids = explode(',',str_replace(' ','',$location['parent_id']));
				if(in_array($post->post_parent, $parent_ids)) {$add_box = true;}
				
			}
			
			if($add_box == true)
			{
				// Override
				if($location['ignore_other_acf'] == 'true')
				{
					// if ignore other acf's was ticked, override the $add_acf array and break the loop
					$add_acf = array($acf);
					break;
				}
				else
				{
					// add acf to array
					$add_acf[] = $acf;
				}
			}
			
		}// end foreach
		
		if(!empty($add_acf))
		{
			// add these acf's to the page
			echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.global.css" />';
			echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.input.css" />';
			echo '<script type="text/javascript" src="'.$this->dir.'/js/swap.jquery.js" ></script>';
			echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.input.js" ></script>';
				
			add_meta_box('acf_input', 'ACF Fields', array($this, '_input_meta_box'), get_post_type($post), 'normal', 'high', array('acfs' => $add_acf));
		}
		
	}// end if
}

?>
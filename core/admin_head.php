<?php

global $post;

		
// shows hidden custom fields
//echo "<style type='text/css'>#postcustom .hidden { display: table-row; }</style>";

// get current page
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];

// do stuff
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
	add_meta_box('acf_location', 'Assign to edit page</span><span class="description">- Create rules to show your advanced custom fields on edit pages', array($this, '_location_meta_box'), 'acf', 'normal', 'high');
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
			$options = $this->get_acf_options($acf->ID);
			
			
			// post type
			if(in_array(get_post_type($post), $location->post_types)) {$add_box = true; }
			
			
			// page title
			if(in_array($post->post_title, $location->page_titles)) {$add_box = true; }
			
			
			// page slug
			if(in_array($post->post_name, $location->page_slugs)) {$add_box = true; }
			
			
			// post id
			if(in_array($post->ID, $location->post_ids)) {$add_box = true; }
			
			
			// page template
			if(in_array(get_post_meta($post->ID,'_wp_page_template',true), $location->page_templates)) {$add_box = true; }
			
			
			// page parents
			if(in_array($post->post_parent, $location->page_parents)) {$add_box = true; }
			
			// category names
			$cats = get_the_category(); 
			if($cats)
			{
				foreach($cats as $cat)
				{
					if(in_array($cat->name, $location->category_names)) {$add_box = true; }
				}
			}
			
			
			
			// current user role
			global $current_user;
			get_currentuserinfo();
			if(!empty($options->user_roles))
			{
				if(!in_array($current_user->user_level, $options->user_roles)) {$add_box = false; }
			}
			

						
			if($add_box == true)
			{
				// Override
				if($location->ignore_other_acfs == '1')
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
			echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.input.js" ></script>';
				
			add_meta_box('acf_input', 'ACF Fields', array($this, '_input_meta_box'), get_post_type($post), 'normal', 'high', array('acfs' => $add_acf));
		}
		
	}// end if
}

?>
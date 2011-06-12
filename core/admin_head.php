<?php

global $post;


// deactivate field
if(isset($_POST['acf_field_deactivate']))
{
	// a field was deactivated
	$field = $_POST['acf_field_deactivate'];
	$option = 'acf_'.$field.'_ac';
	delete_option($option);
	
	// update activated fields
	$this->activated_fields = $this->get_activated_fields();
	$this->fields = $this->_get_field_types();
	
	global $acf_message_field;
	$acf_message_field = ucfirst($field);
	
	function my_admin_notice(){
		global $acf_message_field;
	    echo '<div class="updated below-h2" id="message"><p>'.$acf_message_field.' field deactivated</p></div>';
	}
	add_action('admin_notices', 'my_admin_notice');

}


// activate field
if(isset($_POST['acf_field_activate']))
{
	// a field was deactivated
	$field = $_POST['acf_field_activate'];
	$ac = $_POST['acf_ac'];
	
	$option = 'acf_'.$field.'_ac';
	update_option($option, $ac);
	
	// update activated fields
	$old_count = count($this->activated_fields);
	$this->activated_fields = $this->get_activated_fields();
	$this->fields = $this->_get_field_types();
	$new_count = count($this->activated_fields);
	
	global $acf_message_field;
	$acf_message_field = ucfirst($field);
	
	if($new_count == $old_count)
	{
		function my_admin_notice(){
		    echo '<div class="updated below-h2" id="message"><p>Activation code unrecognized</p></div>';
		}
		add_action('admin_notices', 'my_admin_notice');
		
	}
	else
	{
		function my_admin_notice(){
			global $acf_message_field;
		    echo '<div class="updated below-h2" id="message"><p>'.$acf_message_field.' field activated</p></div>';
		}
		add_action('admin_notices', 'my_admin_notice',$m);
		
	}
	
	
	
}






// get current page
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];


// only add html to post.php and post-new.php pages
if($currentFile == 'post.php' || $currentFile == 'post-new.php')
{
		
	if(get_post_type($post) == 'acf')
	{
	
		// ACF 
		echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.fields.js" ></script>';
		echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.location.js" ></script>';
		
		echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.global.css" />';
		echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.fields.css" />';
		echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.location.css" />';
		echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.options.css" />';
		
		add_meta_box('acf_fields', 'Fields', array($this, '_fields_meta_box'), 'acf', 'normal', 'high');
		add_meta_box('acf_location', 'Location </span><span class="description">- Add Fields to Edit Screens', array($this, '_location_meta_box'), 'acf', 'normal', 'high');
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
				$location = $this->get_acf_location($acf->ID);
				
				
				if($location->allorany == 'all')
				{
					// ALL
					
					$add_box = true;
					
					if($location->rules)
					{
						foreach($location->rules as $rule)
						{
							// if any rules dont return true, dont add this acf
							if(!$this->match_location_rule($post, $rule))
							{
								$add_box = false;
							}
						}
					}
					
				}
				elseif($location->allorany == 'any')
				{
					// ANY
					
					$add_box = false;
					
					if($location->rules)
					{
						foreach($location->rules as $rule)
						{
							// if any rules return true, add this acf
							if($this->match_location_rule($post, $rule))
							{
								$add_box = true;
							}
						}
					}
				}
				
				//$options = $this->get_acf_options($acf->ID);
				/*
				
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
				
				*/
							
				if($add_box == true)
				{
					$add_acf[] = $acf;
				}
				
			}// end foreach
			
			if(!empty($add_acf))
			{
				// add these acf's to the page
				echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.global.css" />';
				echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.input.css" />';
				echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.input.js" ></script>';
				
				// date picker!
				echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/core/fields/date_picker/style.date_picker.css" />';
				echo '<script type="text/javascript" src="'.$this->dir.'/core/fields/date_picker/jquery.ui.datepicker.js" ></script>';
					
				add_meta_box('acf_input', 'ACF Fields', array($this, '_input_meta_box'), get_post_type($post), 'normal', 'high', array('acfs' => $add_acf));
			}
			
		}// end if
	}
}


?>
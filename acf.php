<?php
/*
Plugin Name: Advanced Custom Fields
Plugin URI: http://plugins.elliotcondon.com/advanced-custom-fields/
Description: Completely Customise your edit pages with an assortment of field types: Wysiwyg, text, image, select, checkbox and more! Hide unwanted metaboxes and assign to any edit page!
Version: 1.0.5
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright: Elliot Condon
*/


$acf = new Acf();
include('core/api.php');

class Acf
{ 
	var $name;
	var $dir;
	var $path;
	var $siteurl;
	var $wpadminurl;
	var $version;
	var $fields;
	
	function Acf()
	{
		
		// set class variables
		$this->name = 'Advanced Custom Fields';
		$this->path = dirname(__FILE__).'';
		$this->dir = plugins_url('',__FILE__);
		$this->siteurl = get_bloginfo('url');
		$this->wpadminurl = admin_url();
		$this->version = '1.0.5';
		
		// set text domain
		load_plugin_textdomain('acf', false, $this->path.'/lang' );
		
		// populate post types
		$this->fields = $this->_get_field_types();

		
		// add actions
		add_action('init', array($this, '_init'));
		add_action('admin_head', array($this,'_admin_head'));
		add_action('admin_menu', array($this,'_admin_menu'));
		add_action('save_post', array($this, '_save_post'));
		add_action('admin_footer-edit.php', array($this, '_admin_footer'));
				
		//register_activation_hook(__FILE__, array($this,'activate'));
		
		return true;
	}

	
	/*---------------------------------------------------------------------------------------------
	 * Init
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _init()
	{	
		// create acf post type
		$this->_acf_post_type();
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Save Post
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _save_post($post_id)
	{
		// do not save if this is an auto save routine
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
		
		// verify this with nonce because save_post can be triggered at other times
		if (!wp_verify_nonce($_POST['ei_noncename'], 'ei-n')) return $post_id;
		
		// set post ID if is a revision
		if(wp_is_post_revision($post_id)) 
		{
			$post_id = wp_is_post_revision($post_id);
		}
		
		// delete _acf custom fields if needed
		if($_POST['fields_meta_box'] == 'true' || $_POST['location_meta_box'] == 'true' || $_POST['input_meta_box'] == 'true')
		{
			$this->delete_acf_custom_fields($post_id);
		}
		
		// include meta box save files
		include('core/fields_save.php');
		include('core/location_save.php');
		include('core/options_save.php');
		include('core/input_save.php');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Create ACF Post Type 
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _acf_post_type()
	{
		include('core/acf_post_type.php');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Admin Menu
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _admin_menu() {
	
		// add sub menu
		add_submenu_page('options-general.php', 'CFA', __('Adv Custom Fields','acf'), 'manage_options','edit.php?post_type=acf');

		// remove acf menu item
		global $menu;
		$restricted = array('Advanced&nbsp;Custom&nbsp;Fields');
		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
		
	}
	
	/*---------------------------------------------------------------------------------------------
	 * Admin Head
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _admin_head()
	{
		include('core/admin_head.php');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * activate
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function activate()
	{
		//include('core/update.php');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * _get_field_types
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _get_field_types()
	{
		$array = array();
		
		include('core/fields/text.php');
		include('core/fields/textarea.php');
		include('core/fields/wysiwyg.php');
		include('core/fields/image.php');
		include('core/fields/file.php');
		include('core/fields/select.php');
		include('core/fields/checkbox.php');
		include('core/fields/page_link.php');
		include('core/fields/post_object.php');
		include('core/fields/date_picker/date_picker.php');
		
		$array['text'] = new Text(); 
		$array['textarea'] = new Textarea(); 
		$array['wysiwyg'] = new Wysiwyg(); 
		$array['image'] = new Image($this->dir); 
		$array['file'] = new File($this->dir); 
		$array['select'] = new Select($this); 
		$array['checkbox'] = new Checkbox();
		$array['page_link'] = new Page_link($this);
		$array['post_object'] = new Post_object($this);
		$array['date_picker'] = new Date_picker($this->dir);
		
		return $array;
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * create_field
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function create_field($options)
	{
		if(!$this->fields[$options['type']])
		{
			echo 'error: Field Type does not exist!';
			return false;
		}
		
		$this->fields[$options['type']]->html($options);
	}
	
	/*---------------------------------------------------------------------------------------------
	 * save_field
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function save_field($options)
	{
		if(!$this->fields[$options['field_type']])
		{
			echo 'error: Field Type does not exist!';
			return false;
		}
		
		$this->fields[$options['field_type']]->save_field($options['post_id'], $options['field_name'], $options['field_value']);
	}
	

	/*---------------------------------------------------------------------------------------------
	 * Add Meta Box to the ACF post type edit page
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _fields_meta_box()
	{
		include('core/fields_meta_box.php');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Add Meta Box to the ACF post type edit page
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _location_meta_box()
	{
		include('core/location_meta_box.php');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Add Meta Box to the selected post type edit page
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _input_meta_box($post, $args)
	{
		include('core/input_meta_box.php');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Add Meta Box to the ACF post type edit page
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _options_meta_box()
	{
		include('core/options_meta_box.php');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * delete_acf_custom_fields
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function delete_acf_custom_fields($post_id)
	 {
	 	
		foreach(get_post_custom($post_id) as $key => $values)
		{
			if(strpos($key, '_acf') !== false)
			{
				// this custom field needs to be deleted!
				delete_post_meta($post_id, $key);
			}
		}
	 }
	 
	 /*---------------------------------------------------------------------------------------------
	 * get_fields
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_fields($acf_id)
	 {
	 	$keys = get_post_custom_keys($acf_id);
		
		if(empty($keys))
		{
			return null;
		}
		
	 	$fields = array();
	 	for($i = 0; $i < 99; $i++)
		{
			if(in_array('_acf_field_'.$i.'_label',$keys))
			{
				$field = array(
					'label'		=>	get_post_meta($acf_id, '_acf_field_'.$i.'_label', true),
					'name'		=>	get_post_meta($acf_id, '_acf_field_'.$i.'_name', true),
					'type'		=>	get_post_meta($acf_id, '_acf_field_'.$i.'_type', true),
					'options'	=> 	$this->string_to_clean_array(
										get_post_meta($acf_id, '_acf_field_'.$i.'_options', true)
									),
				);
				
				// if matrix, it will have sub fields
				for($j = 0; $j < 99; $j++)
				{
					if(in_array('_acf_field_'.$i.'_field_'.$j.'_label',$keys))
					{
						$field['options']['repeaters'][] = array(
							'label'		=>	get_post_meta($acf_id, '_acf_field_'.$i.'_field_'.$j.'_label', true),
							'name'		=>	get_post_meta($acf_id, '_acf_field_'.$i.'_field_'.$j.'_name', true),
							'type'		=>	get_post_meta($acf_id, '_acf_field_'.$i.'_field_'.$j.'_type', true),
						);
					}
					else
					{
						// data doesnt exist, break loop
						//echo 'not in array, field = '.$field['label'].'<br>';
						break;
					}
				}	

				$fields[] = $field;
				
			}
			else
			{
				// data doesnt exist, break loop
				break;
			}
		}
		
		return $fields;
	 }
	 
	 /*---------------------------------------------------------------------------------------------
	 * get_field_options
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_field_options($type, $options)
	 {
	 	$field_options = $this->fields[$type]->options();
	 	
	 	?>
	 	<table class="field_options">
	 		<?php foreach($field_options as $field_option): ?>
			<tr>
				<td class="label">
					<label for="post_type"><?php echo $field_options[0]['label'] ?></label>
				</td>
				<td>
					<?php $acf->create_field('text',$options); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	 	<?php
	 }
	 
	 /*---------------------------------------------------------------------------------------------
	 * get_acf_location
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_acf_location($acf_id)
	 {
	 	$location = array(
			'post_type'		=>	get_post_meta($acf_id, '_acf_location_post_type', true),	
			'page_slug'		=>	get_post_meta($acf_id, '_acf_location_page_slug', true),
			'post_id'		=>	get_post_meta($acf_id, '_acf_location_post_id', true),
			'page_template'	=>	get_post_meta($acf_id, '_acf_location_page_template', true),
			'parent_id'		=>	get_post_meta($acf_id, '_acf_location_parent_id', true),
			'ignore_other_acf'	=>	get_post_meta($acf_id, '_acf_location_ignore_other_acf', true),
		);
		
		// post type needs to be in array format
		//$location['post_type'] = str_replace(', ',',',$location['post_type']);
		//$location['post_type'] = explode(',',$location['post_type']);
			
		return $location;
	 }

	
	/*---------------------------------------------------------------------------------------------
	 * get_acf_options
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_acf_options($acf_id)
	 {
	 	$options = array();
	 	
	 	$keys = get_post_custom_keys($acf_id);
	 	
	 	if(empty($keys))
		{
			$options['show_on_page'] = 'the_content, discussion, custom_fields, comments, slug, author';
		}
		else
	 	{
	 		$options['show_on_page'] = get_post_meta($acf_id, '_acf_option_show_on_page', true);
	 	}
	 	
		return $options;
	 }
	
	
	/*---------------------------------------------------------------------------------------------
	 * add_to_Edit_screen
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function add_to_edit_screen()
	{
	
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * string_to_clean_array
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function string_to_clean_array($string)
	 {
	 	if(!is_array(unserialize($string)))
	 	{
	 		return array();
	 	}
	 	
	 	$array = unserialize($string);
	 	/*print_r($string);
	 	
		foreach($array as $key => $value)
		{
			if(is_array($value)) // options is an array, so unserialize it and strip slashes
			{
				$child_array = array();
				foreach($value as $child_key => $child_value)
				{
					$child_array[$child_key] = stripslashes($child_value);
				}
				$value[$key] = $child_array;
			}
			else // everythis else is a simple string.
			{
				$array[$key] = stripslashes($value);
			}	
		}*/
		return $array;
	 }
	 
	 
	 /*---------------------------------------------------------------------------------------------
	 * get_adv_options
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_adv_options($acf_id)
	 {
	 	$adv = array();
	 	
	 	$adv['show_on_page'] = get_post_meta($acf_id, '_acf_option_show_on_page', true);
	 	
	 	if(empty($adv['show_on_page']))
		{
			$adv['show_on_page'] = array();
		}
		else
		{
			$adv['show_on_page'] = str_replace(', ',',',$adv['show_on_page']);
			$adv['show_on_page'] = explode(',',$adv['show_on_page']);
		}
					
		return $adv;
	 }
	 
	 /*---------------------------------------------------------------------------------------------
	 * admin_footer
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function _admin_footer()
	{
		if($_GET['post_type'] != 'acf'){return false;}
		
		echo "<style type='text/css'>.row-actions span.inline, .row-actions span.view { display: none; }</style>";
		echo '<link rel="stylesheet" href="'.$this->dir.'/css/style.info.css" type="text/css" media="all" />';
		echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.info.js"></script>';
		include('core/info_meta_box.php');
	}
	 
	 
	
}
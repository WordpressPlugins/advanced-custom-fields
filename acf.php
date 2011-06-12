<?php
/*
Plugin Name: Advanced Custom Fields
Plugin URI: http://plugins.elliotcondon.com/advanced-custom-fields/
Description: Completely Customise your edit pages with an assortment of field types: Wysiwyg, Repeater, text, textarea, image, file, select, checkbox post type, page link and more! Hide unwanted metaboxes and assign to any edit page!
Version: 2.0.1
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright: Elliot Condon
*/

//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);

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
	var $activated_fields;
	
	function Acf()
	{
		
		// set class variables
		$this->name = 'Advanced Custom Fields';
		$this->path = dirname(__FILE__).'';
		$this->dir = plugins_url('',__FILE__);
		$this->siteurl = get_bloginfo('url');
		$this->wpadminurl = admin_url();
		$this->version = '2.0.1';
		$this->activated_fields = $this->get_activated_fields();
		
		
		// set text domain
		load_plugin_textdomain('acf', false, $this->path.'/lang' );
		
		
		// populate post types
		$this->fields = $this->_get_field_types();

		
		// add actions
		add_action('init', array($this, '_init'));
		add_action('admin_head', array($this,'_admin_head'));
		add_action('admin_menu', array($this,'_admin_menu'));
		add_action('save_post', array($this, '_save_post'));
		add_action('admin_footer', array($this, '_admin_footer'));
		
		
		
		// add thickbox
		add_action("admin_print_scripts", array($this, '_admin_print_scripts'));
	    add_action("admin_print_styles", array($this, '_admin_print_styles'));
			
			
		// update if versions don't match
		if(get_option('acf_version') != $this->version)
		{
			$this->update();
		}

		
		return true;
	}

	/*---------------------------------------------------------------------------------------------
	 * Update
	 *
	 * @author Elliot Condon
	 * @since 1.0.6
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function update()
	{
		include('core/update.php');
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
	
	function _admin_print_scripts()
	{
		$currentFile = $_SERVER["SCRIPT_NAME"];
		$parts = Explode('/', $currentFile);
		$currentFile = $parts[count($parts) - 1];
		
		if($currentFile == 'edit.php' && $_GET['post_type'] == 'acf')
		{
			wp_enqueue_script('thickbox');
		}
	}
	
	function _admin_print_styles()
	{
		$currentFile = $_SERVER["SCRIPT_NAME"];
		$parts = Explode('/', $currentFile);
		$currentFile = $parts[count($parts) - 1];
		
		if($currentFile == 'edit.php' && $_GET['post_type'] == 'acf')
		{
			wp_enqueue_style('thickbox');
		}
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
		
		// only save once! WordPress save's twice for some strange reason.
		global $flag;
		if ($flag != 0) return $post_id;
		$flag = 1;
		
		// set post ID if is a revision
		if(wp_is_post_revision($post_id)) 
		{
			$post_id = wp_is_post_revision($post_id);
		}
		
		// delete _acf custom fields if needed
		if(isset($_POST['fields_meta_box']) || isset($_POST['location_meta_box']) || isset($_POST['input_meta_box']))
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
		$restricted = array(__('Advanced&nbsp;Custom&nbsp;Fields','acf'));
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
		
		include_once('core/fields/text.php');
		include_once('core/fields/textarea.php');
		include_once('core/fields/wysiwyg.php');
		include_once('core/fields/image.php');
		include_once('core/fields/file.php');
		include_once('core/fields/select.php');
		include_once('core/fields/checkbox.php');
		include_once('core/fields/true_false.php');
		include_once('core/fields/page_link.php');
		include_once('core/fields/post_object.php');
		include_once('core/fields/date_picker/date_picker.php');
		include_once('core/fields/repeater.php');
		
		$array['text'] = new acf_Text(); 
		$array['textarea'] = new acf_Textarea(); 
		$array['wysiwyg'] = new acf_Wysiwyg(); 
		$array['image'] = new acf_Image(); 
		$array['file'] = new acf_File(); 
		$array['select'] = new acf_Select($this); 
		$array['checkbox'] = new acf_Checkbox();
		$array['true_false'] = new acf_True_false();
		$array['page_link'] = new acf_Page_link($this);
		$array['post_object'] = new acf_Post_object($this);
		$array['date_picker'] = new acf_Date_picker($this->dir);
		
		if(array_key_exists('repeater', $this->activated_fields))
		{
			$array['repeater'] = new acf_Repeater($this);
		}
		
		return $array;
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * create_field
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function create_field($field)
	{
		if(!is_object($this->fields[$field->type]))
		{
			_e('Error: Field Type does not exist!','acf');
			return false;
		}
		
		$this->fields[$field->type]->html($field);
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
			_e('Error: Field Type does not exist!','acf');
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
	 
	 	// set table name
		global $wpdb;
		$table_name = $wpdb->prefix.'acf_fields';
	 	
	 	
	 	// get fields
	 	$parent_id = 0;
	 	$fields = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id = '$acf_id' AND parent_id = $parent_id ORDER BY order_no,name");
	 	
	 	
	 	// if fields are empty, this must be a new or broken acf. add blank field
	 	if(empty($fields))
	 	{
	 		return array();
	 	}
	 	

		// loop through fields
	 	foreach($fields as $field)
	 	{
	 		
			// unserialize options
			$field->options = @unserialize($field->options);
			
			if(!is_array($field->options))
			{
				$field->options = array();
			}

	 		
	 		// sub fields
	 		if($field->type == 'repeater')
	 		{
	 			$sub_fields = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_id = '$field->id' ORDER BY order_no,name");

	 			
	 			// if fields are empty, this must be a new or broken acf. 
			 	if(empty($sub_fields))
			 	{
			 		$field->options['sub_fields'] = array();
			 	}
			 	else
			 	{
			 		// loop through fields
				 	foreach($sub_fields as $sub_field)
				 	{
				 		// unserialize options
		 				$sub_field->options = unserialize($sub_field->options);
					}
					
					
					// assign array to the field options array
					$field->options['sub_fields'] = $sub_fields;
			 	}
			 	
			 	
						 	
	 		}
	 		// end if sub field
	 	}
	 	// end foreach $fields
	 	
	 	// return fields
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

	 	// set table name
		global $wpdb;
		$table_name = $wpdb->prefix.'acf_options';
	 	
	 	
	 	// get fields and add them to $options
	 	$location = new stdClass();
	 	$db_locations = $wpdb->get_results("SELECT name, value FROM $table_name WHERE acf_id = '$acf_id' AND type = 'location'");
	 	
	 	foreach($db_locations as $db_location)
	 	{
	 		$key = $db_location->name;
	 		$value = $db_location->value;
	 		$location->$key = $value;
	 	}
	 	
	 	
	 	// if empty
	 	if(empty($location->post_types)){$location->post_types = serialize(array());}
	 	if(empty($location->page_titles)){$location->page_titles = serialize(array());}
	 	if(empty($location->page_slugs)){$location->page_slugs = serialize(array());}
	 	if(empty($location->post_ids)){$location->post_ids = serialize(array());}
	 	if(empty($location->page_templates)){$location->page_templates = serialize(array());}
	 	if(empty($location->page_parents)){$location->page_parents = serialize(array());}
	 	if(empty($location->category_names)){$location->category_names = serialize(array());}
		if(empty($location->ignore_other_acfs)){$location->ignore_other_acfs = '0';}
		
		
	 	// unserialize values
		$location->post_types = unserialize($location->post_types);
		$location->page_titles = unserialize($location->page_titles);
		$location->page_slugs = unserialize($location->page_slugs);
		$location->post_ids = unserialize($location->post_ids);
		$location->page_templates = unserialize($location->page_templates);
		$location->page_parents = unserialize($location->page_parents);
		$location->category_names = unserialize($location->category_names);
	 	
	 	
	 	
	 	
	 	
	 	// return location
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
	 	$options = new stdClass();
	 	
	 
	 	// If this is a new acf, there will be no custom keys!
	 	if(!get_post_custom_keys($acf_id))
	 	{
	 		$options->show_on_page = array('the_content', 'discussion', 'custom_fields', 'comments', 'slug', 'author');
			$options->user_roles = array();
			
			return $options;
	 	}
	 	
	 	
	 	// set table name
		global $wpdb;
		$table_name = $wpdb->prefix.'acf_options';
	 	
	 	
	 	// get fields and add them to $options
	 	$db_options = $wpdb->get_results("SELECT name, value FROM $table_name WHERE acf_id = '$acf_id' AND type = 'option'");
	 	
	 	foreach($db_options as $db_option)
	 	{
	 		$key = $db_option->name;
	 		$value = $db_option->value;
	 		$options->$key = $value;
	 	}
	 	
	 	
	 	// if empty
	 	if(empty($options->show_on_page)){$options->show_on_page = serialize(array());}
	 	if(empty($options->user_roles)){$options->user_roles = serialize(array());}


	 	// unserialize options
		$options->show_on_page = unserialize($options->show_on_page);
		$options->user_roles = unserialize($options->user_roles);

	 	
	 	// return fields
	 	return $options;

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
		global $post;
		
		if(isset($_GET['post_type']) && $_GET['post_type'] == 'acf')
		{
			echo '<link rel="stylesheet" type="text/css" href="'.$this->dir.'/css/style.screen_extra.css" />';
			echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.screen_extra.js" ></script>';
			include('core/screen_extra.php');
		}
		
	}
	
	/*---------------------------------------------------------------------------------------------
	 * load_value_for_input
	 *
	 * @author Elliot Condon
	 * @since 1.0.6
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function load_value_for_input($post_id, $field)
	{
		if(method_exists($this->fields[$field->type], 'load_value_for_input'))
		{
			$value = $this->fields[$field->type]->load_value_for_input($post_id, $field);
		}
		else
		{
			// set table name
			global $wpdb;
			$table_name = $wpdb->prefix.'acf_values';
		 	
		 	
		 	// get row
		 	$value = $wpdb->get_var("SELECT value FROM $table_name WHERE field_id = '$field->id' AND post_id = '$post_id'");

		}
		
		
		// format if needed
		if(method_exists($this->fields[$field->type], 'format_value_for_input'))
		{
			$value = $this->fields[$field->type]->format_value_for_input($value);
		}
		

		// return value
		return $value;
	}

	
	
	/*---------------------------------------------------------------------------------------------
	 * load_value_for_input
	 *
	 * @author Elliot Condon
	 * @since 1.0.6
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function load_value_for_api($post_id, $field)
	{
		if(method_exists($this->fields[$field->type], 'load_value_for_api'))
		{
			$value = $this->fields[$field->type]->load_value_for_api($post_id, $field);
		}
		else
		{
			// set table name
			global $wpdb;
			$table_name = $wpdb->prefix.'acf_values';
		 	
		 	
		 	// get var
		 	$value = $wpdb->get_var("SELECT value FROM $table_name WHERE field_id = '$field->id' AND post_id = '$post_id'");
		 	//$value = stripslashes($value);

		 	
		 	// format if needed
			if(method_exists($this->fields[$field->type], 'format_value_for_api'))
			{
				$value = $this->fields[$field->type]->format_value_for_api($value);
			}
		}
		
		
		
		
		
		// return value
		return $value;
	}
	 
	
	/*---------------------------------------------------------------------------------------------
	 * get_activated_fields
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function get_activated_fields()
	{
		$activated = array();
		
		// repeater
		if(get_option("acf_repeater_ac"))
		{
			$md5 = md5(get_option("acf_repeater_ac"));
			if($md5 == "bbefed143f1ec106ff3a11437bd73432")
			{
				$activated['repeater'] = get_option("acf_repeater_ac");
			}
			if($md5 == "44146dd6d0f8873f34e4a0b75e5639f7")
			{
				$activated['repeater'] = get_option("acf_repeater_ac")." (Testing License)";
			}
		}
		
		return $activated;
	}

	
}


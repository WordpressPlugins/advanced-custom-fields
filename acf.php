<?php
/*
Plugin Name: Advanced Custom Fields
Plugin URI: http://plugins.elliotcondon.com/advanced-custom-fields/
Description: Completely Customise your edit pages with an assortment of field types: Wysiwyg, text, image, select, checkbox and more! Hide unwanted metaboxes and assign to any edit page!
Version: 1.1.0
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
		$this->version = '1.1.0';
		
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
		
		// add thickbox
		add_action("admin_print_scripts", array($this, '_admin_print_scripts'));
	    add_action("admin_print_styles", array($this, '_admin_print_styles'));
				
		//register_activation_hook(__FILE__, array($this,'activate'));
		
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
		
		if($currentFile == 'edit.php')
		{
			wp_enqueue_script('thickbox');
		}
	}
	
	function _admin_print_styles()
	{
		$currentFile = $_SERVER["SCRIPT_NAME"];
		$parts = Explode('/', $currentFile);
		$currentFile = $parts[count($parts) - 1];
		
		if($currentFile == 'edit.php')
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
		include('core/fields/true_false.php');
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
		$array['true_false'] = new True_false();
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
	function create_field($field)
	{
		if(!$this->fields[$field->type])
		{
			echo 'error: Field Type does not exist!';
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
	 
	 	// If this is a new acf, there will be no custom keys!
	 	if(!get_post_custom_keys($acf_id))
	 	{
	 		$fields = array();
	 		$field = new stdClass();
			$field->label = '';
			$field->name = '';
			$field->type = '';
			$field->options = array();
	 		
	 		$fields[] = $field;
			return $fields;
	 	}
	 	
	 	// set table name
		global $wpdb;
		$table_name = $wpdb->prefix.'acf_fields';
	 	
	 	
	 	// get fields
	 	$fields = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id = '$acf_id' ORDER BY order_no,name");
	 	

		// loop through fields
	 	foreach($fields as $field)
	 	{
			// unserialize options
	 		$field->options = unserialize($field->options);
	 	}
	 	
	 	
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
	 	
	 	
	 	// unserialize values
		$location->post_types = unserialize($location->post_types);
		$location->page_titles = unserialize($location->page_titles);
		$location->page_slugs = unserialize($location->page_slugs);
		$location->post_ids = unserialize($location->post_ids);
		$location->page_templates = unserialize($location->page_templates);
		$location->page_parents = unserialize($location->page_parents);
	 	
	 	
	 	// if empty
	 	if(empty($location->post_types)){$location->post_types = array();}
	 	if(empty($location->page_titles)){$location->page_titles = array();}
	 	if(empty($location->page_slugs)){$location->page_slugs = array();}
	 	if(empty($location->post_ids)){$location->post_ids = array();}
	 	if(empty($location->page_templates)){$location->page_templates = array();}
	 	if(empty($location->page_parents)){$location->page_parents = array();}
	 	if(empty($location->page_parents)){$location->page_parents = array();}
	 	
	 	
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
			$options->user_roles = array('10', '7', '4', '1');
			
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
	 	

	 	// unserialize options
		$options->show_on_page = unserialize($options->show_on_page);
		$options->user_roles = unserialize($options->user_roles);
	 	
	 	
	 	// if empty
	 	if(empty($options->show_on_page)){$options->show_on_page = array();}
	 	if(empty($options->user_roles)){$options->user_roles = array('10', '7', '4', '1');}

	 	
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
		if($_GET['post_type'] != 'acf'){return false;}
		
		echo "<style type='text/css'>.row-actions span.inline, .row-actions span.view { display: none; }</style>";
		echo '<link rel="stylesheet" href="'.$this->dir.'/css/style.info.css" type="text/css" media="all" />';
		echo '<script type="text/javascript" src="'.$this->dir.'/js/functions.info.js"></script>';
		include('core/info_meta_box.php');
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
		 	
		 	
		 	// get var
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
		}
		
		
		// format if needed
		if(method_exists($this->fields[$field->type], 'format_value_for_api'))
		{
			$value = $this->fields[$field->type]->format_value_for_api($value);
		}
		
		
		// return value
		return $value;
	}
	 
	
}

// update on activate
register_activation_hook( __FILE__, array('Acf', 'update') );
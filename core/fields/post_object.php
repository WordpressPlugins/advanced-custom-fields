<?php

class Post_object
{
	var $name;
	var $title;
	var $parent;
	
	function Post_object($parent)
	{
		$this->name = 'post_object';
		$this->title = __("Post Object",'acf');
		$this->parent = $parent;
	}
	
	function html($field)
	{
		// get post types
		if(is_array($field->options['post_type']))
		{
			// 1. If select has selected post types, just use them
			$post_types = $field->options['post_type'];
		}
		else
		{
			//2. If not post types have been selected, load all the public ones
			$post_types = get_post_types(array('public' => true));
			foreach($post_types as $key => $value)
			{
				if($value == 'attachment')
				{
					unset($post_types[$key]);
				}
			}
		}
		
		
		// start select
		if($field->options['multiple'] == '1')
		{
			$name_extra = '[]';
			echo '<select id="'.$field->input_id.'" class="'.$field->input_class.'" name="'.$field->input_name.$name_extra.'" multiple="multiple" size="5" >';
		}
		else
		{
			echo '<select id="'.$field->input_id.'" class="'.$field->input_class.'" name="'.$field->input_name.'" >';	
			// add top option
			echo '<option value="null">- '.__("Select Option",'acf').' -</option>';
		}
		
		
		foreach($post_types as $post_type)
		{
			// get posts
			$posts = get_posts(array(
				'numberposts' 	=> 	-1,
				'post_type'		=>	$post_type,
				'orderby'		=>	'title',
				'order'			=>	'ASC'
			));
			
			
			// if posts, make a group for them
			if($posts)
			{
				echo '<optgroup label="'.$post_type.'">';
				
				foreach($posts as $post)
				{
					$key = $post->ID;
					$value = get_the_title($post->ID);
					$selected = '';
					
					
					if(is_array($field->value))
					{
						// 2. If the value is an array (multiple select), loop through values and check if it is selected
						if(in_array($key, $field->value))
						{
							$selected = 'selected="selected"';
						}
					}
					else
					{
						// 3. this is not a multiple select, just check normaly
						if($key == $field->value)
						{
							$selected = 'selected="selected"';
						}
					}	
					
					
					echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
					
					
				}	
				
				echo '</optgroup>';
				
			}// endif
		}// endforeach
		

		echo '</select>';
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Options HTML
	 * - called from fields_meta_box.php
	 * - displays options in html format
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function options_html($key, $options)
	{
		?>
		<table class="acf_input">
		<tr>
			<td class="label">
				<label for=""><?php _e("Post Type",'acf'); ?></label>
			</td>
			<td>
				<?php 
				
				foreach (get_post_types() as $post_type ) {
				  $post_types[$post_type] = $post_type;
				}
				
				unset($post_types['attachment']);
				unset($post_types['nav_menu_item']);
				unset($post_types['revision']);
				unset($post_types['acf']);
				

				$temp_field = new stdClass();	
				$temp_field->type = 'select';
				$temp_field->input_name = 'acf[fields]['.$key.'][options][post_type]';
				$temp_field->input_class = '';
				$temp_field->input_id = 'acf[fields]['.$key.'][options][post_type]';
				$temp_field->value = $options['post_type'];
				$temp_field->options = array('choices' => $post_types, 'multiple' => '1');
				$this->parent->create_field($temp_field); 
				
				?>
				<p class="description"><?php _e("Filter posts by selecting a post type<br />
				* unselecting all is the same as selecting all",'acf'); ?></p>
			</td>
		</tr>
		<tr>
			<td class="label">
				<label><?php _e("Multiple?",'acf'); ?></label>
			</td>
			<td>
				<?php 
					$temp_field = new stdClass();	
					$temp_field->type = 'true_false';
					$temp_field->input_name = 'acf[fields]['.$key.'][options][multiple]';
					$temp_field->input_class = '';
					$temp_field->input_id = 'acf[fields]['.$key.'][options][multiple]';
					$temp_field->value = $options['multiple'];
					$temp_field->options = array('message' => 'Select multiple posts');
					$this->parent->create_field($temp_field); 
				?>
			</td>
		</tr>
		</table>
		<?php
	}
	
	
	
	/*---------------------------------------------------------------------------------------------
	 * Save Input
	 * - this is called from save_input.php, this function saves the field's value(s)
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function save_input($post_id, $field)
	{
		// set table name
		global $wpdb;
		$table_name = $wpdb->prefix.'acf_values';
		
		
		// if select is a multiple, you need to save it as an array!
		if(is_array($field['value']))
		{
			$field['value'] = serialize($field['value']);
		}
		
		
		// insert new data
		$new_id = $wpdb->insert($table_name, array(
			'post_id'	=>	$post_id,
			'field_id'	=>	$field['field_id'],
			'value'		=>	$field['value']
		));
	}
	
	/*---------------------------------------------------------------------------------------------
	 * Format Value
	 * - this is called from api.php
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function format_value_for_api($value)
	{
		$value = $this->format_value_for_input($value);

		if(is_array($value))
		{
			foreach($value as $k => $v)
			{
				$value[$k] = get_post($v);
			}
		}
		else
		{
			$value = get_post($value);
		}
		
		return $value;
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Format Value for input
	 * - this is called from api.php
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function format_value_for_input($value)
	{
		if(is_array(unserialize($value)))
		{
			return(unserialize($value));
		}
		else
		{
			return $value;
		}
		
	}
	
}

?>
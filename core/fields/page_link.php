<?php

class Page_link
{
	var $name;
	var $title;
	var $parent;
	
	function Page_link($parent)
	{
		$this->name = 'page_link';
		$this->title = 'Page Link';
		$this->parent = $parent;
	}
	
	function html($options)
	{
		// get post types
		if(is_array($options['options']['post_type']))
		{
			// 1. If select has selected post types, just use them
			$post_types = $options['options']['post_type'];
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
		

		$posts = get_posts(array(
			'numberposts' 	=> 	-1,
			'post_type'		=>	$post_types,
			'orderby'		=>	'title',
			'order'			=>	'ASC'
		));
		
		$choices = array();
		if($posts)
		{
			foreach($posts as $post)
			{
				$title = get_the_title($post->ID);
				
				if(strlen($title) > 33)
				{
					$title = substr($title,0,30).'...';
				}
				
				$choices[$post->ID] = $title.' ('.get_post_type($post->ID).')';
			}			
		}
		else
		{
			$choices[] = null;
		}
		
		$options['options']['choices'] = $choices;
		
		
		
		echo '<select id="'.$options['id'].'" class="'.$options['class'].'" name="'.$options['name'].'" >';
		
		// add top option
		echo '<option value="">- Select Option -</option>';
		
		// loop through values and add them as options
		foreach($options['options']['choices'] as $key => $value)
		{
			$selected = '';
			if($options['value'] == $key)
			{
				$selected = 'selected="selected"';
			}
			echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}

		echo '</select>';
	}
	
	function has_options()
	{
		return true;
	}
	
	function options($key, $options)
	{
		?>
		<table class="acf_input">
		<tr>
			<td class="label">
				<label for="">Post Type</label>
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
				
				$this->parent->create_field(array('type'=>'select','name'=>'acf[fields]['.$key.'][options][post_type]','value'=>$options['post_type'],'id'=>'acf[fields]['.$key.'][options][post_type]', 'options' => array('choices' => $post_types, 'multiple' => 'true'))); 
				?>
				<p class="description">Filter posts by selecting a post type</p>
			</td>
		</tr>
		</table>
		<?php
	}
	
	function has_format_value()
	{
		return true;
	}
	
	function format_value($value)
	{
		$value = get_permalink($value);
	
		return $value;
	}
	
	function save_field($post_id, $field_name, $field_value)
	{
		// this is a normal text save
		add_post_meta($post_id, '_acf_'.$field_name, $field_value);
	}
	
}

?>
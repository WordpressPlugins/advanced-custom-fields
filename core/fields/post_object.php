<?php

class Post_object
{
	var $name;
	var $title;
	var $parent;
	
	function Post_object($parent)
	{
		$this->name = 'post_object';
		$this->title = 'Post Object';
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
		
		$this->parent->create_field(array(
			'type'=>'select',
			'name'=>$options['name'],
			'value'=>$options['value'],
			'id'=>$options['name'],
			'options' => $options['options']
		)); 
		
		
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
		<tr>
			<td class="label">
				<label>Multiple?</label>
			</td>
			<td>
				<?php $this->parent->create_field(array(
					'type'=>'checkbox',
					'name'=>'acf[fields]['.$key.'][options][multiple]',
					'value'=>$options['multiple'],
					'id'=>'acf[fields]['.$key.'][options][multiple]', 
					'options' => array('choices' => array('true' => 'Select multiple posts'))
				)); ?>
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
	
	function save_field($post_id, $field_name, $field_value)
	{
		// this is a normal text save
		add_post_meta($post_id, '_acf_'.$field_name, $field_value);
	}
	
}

?>
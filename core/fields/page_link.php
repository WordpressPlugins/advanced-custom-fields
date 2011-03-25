<?php

class Page_link
{
	var $name;
	var $title;
	
	function Page_link()
	{
		$this->name = 'page_link';
		$this->title = 'Page Link';
	}
	
	function html($options)
	{
		
		$post_types = get_post_types(array('public' => true));
		foreach($post_types as $key => $value)
		{
			if($value == 'attachment')
			{
				unset($post_types[$key]);
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
		return false;
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
	
}

?>
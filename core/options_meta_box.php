<?php
	
	global $post;
		
	// get options
	$options = $this->get_acf_options($post->ID);
	//print_r($options);
?>

<input type="hidden" name="options_meta_box" value="true" />
<input type="hidden" name="ei_noncename" id="ei_noncename" value="<?php echo wp_create_nonce('ei-n'); ?>" />

<table class="acf_input" id="acf_options">
	<tr>
		<td class="label">
			<label for="post_type">Show on page</label>
		</td>
		<td>
			<?php
				$show = array(
					'the_content'	=>	'Content Editor',
					'custom_fields'	=>	'Custom Fields',
					'discussion'	=>	'Discussion',
					'comments'		=>	'Comments',
					'slug'			=>	'Slug',
					'author'		=>	'Author'
				);
				
				if(empty($options['show_on_page']))
				{
					$options['show_on_page'] = 'bilbo bagains';
				}
			?>
			<?php $this->create_field(array(
				'type'		=>	'checkbox',
				'name'		=>	'acf[options][show_on_page]',
				'value'		=>	$options['show_on_page'],
				'id'		=>	'show_on_page',
				'options'	=>	array('choices' => $show) 
				)); ?>
			<p class="description">Unselected items will not be shown on the edit screen.<br>This is useful to clean up the edit page</p>
		</td>
	</tr>
	
</table>
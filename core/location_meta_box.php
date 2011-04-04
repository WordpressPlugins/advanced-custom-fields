<?php
	
	global $post;
		
	// get options
	$location = $this->get_acf_location($post->ID);
	
	// if post_type exists, it will be an array and neds to be exploded
	if(is_array($location['post_type']))
	{
		$location['post_type'] = explode(',',str_replace(' ','',$location['post_type']));
	}
	elseif(empty($location['post_type']))
	{
		$location['post_type'] = array('false' => 'false');
	}

	//print_r($location);
	
?>

<input type="hidden" name="location_meta_box" value="true" />
<input type="hidden" name="ei_noncename" id="ei_noncename" value="<?php echo wp_create_nonce('ei-n'); ?>" />

<table class="acf_input" id="acf_location">
	<tr>
		<td class="label">
			<label for="post_type">Post Type's</label>
		</td>
		<td>
			<?php 
			
			$post_types = array();
			
			foreach (get_post_types() as $post_type ) {
			  $post_types[$post_type] = $post_type;
			}
			
			unset($post_types['attachment']);
			unset($post_types['nav_menu_item']);
			unset($post_types['revision']);
			unset($post_types['acf']);
			
			
			$this->create_field(array('type'=>'select','name'=>'acf[location][post_type]','value'=>$location['post_type'],'id'=>'post_type', 'options' => array('choices' => $post_types, 'multiple' => 'true'))); 
			?>
			<p class="description">Selecting a post type here will add this ACF to all edit screens of that post type.<br />(if your custom post type does not appear, make sure it is publicly query-able)<br /><br />
			Tip: Unselect post types and use the options below to customise your ACF location!<br />
			(command+click)</p>
		</td>
	</tr>
	<tr>
		<td class="label">
			<label for="page_slug">Page Slug's</label>
		</td>
		<td>
			<?php $this->create_field(array('type'=>'text','name'=>'acf[location][page_slug]','value'=>$location['page_slug'],'id'=>'page_slug')); ?>
			<p class="description">eg. home, about-us</p>
		</td>
	</tr>
	<tr>
		<td class="label">
			<label for="post_id">Post ID's</label>
		</td>
		<td>
			<?php $this->create_field(array('type'=>'text','name'=>'acf[location][post_id]','value'=>$location['post_id'],'id'=>'post_id')); ?>
			<p class="description">eg. 1, 2, 3</p>
		</td>
	</tr>
	<tr>
		<td class="label">
			<label for="template_name">Page Template's</label>
		</td>
		<td>
			<?php $this->create_field(array('type'=>'text','name'=>'acf[location][page_template]','value'=>$location['page_template'],'id'=>'page_template')); ?>
			<p class="description">eg. home_page.php</p>
		</td>
	</tr>
	<tr>
		<td class="label">
			<label for="page_parent">Page Parent ID's</label>
		</td>
		<td>
			<?php $this->create_field(array('type'=>'text','name'=>'acf[location][parent_id]','value'=>$location['parent_id'],'id'=>'parent_id')); ?>
			<p class="description">eg. 1, 2, 3</p>
		</td>
	</tr>
	<tr>
		<td class="label">
			<label for="page_parent">Overrides</label>
		</td>
		<td>
			<?php $this->create_field(array('type'=>'checkbox','name'=>'acf[location][ignore_other_acf]','value'=>$location['ignore_other_acf'],'id'=>'ignore_other_acf', 'options' => array('choices' => array('true' => 'Ignore all other Advanced Custom Field\'s')))); ?>
			<p class="description">Tick this box to remove all other ACF's <br />(from the edit screen where this ACF appears)</p>
		</td>
	</tr>
</table>
<div id="screen-meta-activate-acf-wrap" class="screen-meta-wrap hidden acf">
	<div class="screen-meta-content">
		
		<h5><?php _e("Unlock Special Fields.",'acf'); ?></h5>
		<p><?php _e("Special Fields can be unlocked by purchasing an activation code. Each activation code can be used on multiple sites.",'acf'); ?> <a href="http://plugins.elliotcondon.com/shop/"><?php _e("Visit the Plugin Store",'acf'); ?></a></p>
		<table class="acf_activate widefat">
			<thead>
				<tr>
					<th><?php _e("Field Type",'acf'); ?></th>
					<th><?php _e("Status",'acf'); ?></th>
					<th><?php _e("Activation Code",'acf'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php _e("Repeater",'acf'); ?></td>
					<td><?php if(array_key_exists('repeater', $this->activated_fields)){
						_e("Active",'acf');
					}
					else
					{
						_e("Inactive",'acf');
					} ?></td>
					<td>
						<form action="" method="post">
							<?php if(array_key_exists('repeater', $this->activated_fields)){
								echo '<span class="activation_code">XXXX-XXXX-XXXX-'.substr($this->activated_fields['repeater'],-4) .'</span>';
								echo '<input type="hidden" name="acf_field_deactivate" value="repeater" />';
								echo '<input type="submit" class="button" value="Deactivate" />';
							}
							else
							{
								echo '<input type="text" name="acf_ac" value="" />';
								echo '<input type="hidden" name="acf_field_activate" value="repeater" />';
								echo '<input type="submit" class="button" value="Activate" />';
							} ?>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div id="screen-meta-activate-acf-link-wrap" class="hide-if-no-js screen-meta-toggle acf">
	<a href="#screen-meta-activate-acf" id="screen-meta-activate-acf-link" class="show-settings"><?php _e("Unlock Fields",'acf'); ?></a>
</div>


<?php
// get current page
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];

if($currentFile == 'edit.php'):
?>


<div class="acf_col_right hidden metabox-holder" id="poststuff" >

	<div class="postbox">
		<div class="handlediv"><br></div>
		<h3 class="hndle"><span><?php _e("Advanced Custom Fields v",'acf'); ?><?php echo $this->version; ?></span></h3>
		<div class="inside">
			<div class="field">
				<h4><?php _e("Changelog",'acf'); ?></h4>
				<p><?php _e("See what's new in",'acf'); ?> <a class="thickbox" href="<?php bloginfo('url'); ?>/wp-admin/plugin-install.php?tab=plugin-information&plugin=advanced-custom-fields&section=changelog&TB_iframe=true&width=640&height=559">v<?php echo $this->version; ?></a>
			</div>
			<div class="field">
				<h4><?php _e("Resources",'acf'); ?></h4>
				<p><?php _e("Watch tutorials, read documentation, learn the API code and find some tips &amp; tricks for your next web project.",'acf'); ?><br />
				<a href="http://plugins.elliotcondon.com/advanced-custom-fields/"><?php _e("View the plugins website",'acf'); ?></a></p>
			</div>
			<div class="field">
				<h4><?php _e("Support",'acf'); ?></h4>
				<p><?php _e("Join the growing community over at the support forum to share ideas, report bugs and keep up to date with ACF",'acf'); ?><br />
				<a href="http://support.plugins.elliotcondon.com/categories/advanced-custom-fields/"><?php _e("View the Support Forum",'acf'); ?></a></p>
			</div>
			<div class="field">
				<h4><?php _e("Developed by",'acf'); ?> Elliot Condon</h4>
				<p><a href="http://wordpress.org/extend/plugins/advanced-custom-fields/"><?php _e("Vote for ACF",'acf'); ?></a> | <a href="http://twitter.com/elliotcondon"><?php _e("Twitter",'acf'); ?></a> | <a href="http://blog.elliotcondon.com"><?php _e("Blog",'acf'); ?></a></p>
			</div>
			
		
		</div>
	</div>
</div>

<?php endif; ?>
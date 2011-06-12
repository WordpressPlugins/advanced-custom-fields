<div id="screen-meta-activate-acf-wrap" class="screen-meta-wrap hidden acf">
	<div class="screen-meta-content">
		
		<h5>Unlock Special Fields.</h5>
		<p>Special Fields can be unlocked by purchasing an activation code. Each activation code can be used on multiple sites. <a href="http://plugins.elliotcondon.com/shop/">Visit the Plugin Store</a></p>
		<table class="acf_activate">
			<thead>
				<tr>
					<th>Field Type</th>
					<th>Status</th>
					<th>Activation Code</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Repeater</td>
					<td><?php if(array_key_exists('repeater', $this->activated_fields)){
						echo 'Active';
					}
					else
					{
						echo 'Not Active';
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
	<a href="#screen-meta-activate-acf" id="screen-meta-activate-acf-link" class="show-settings">Unlock Fields</a>
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
		<div title="Click to toggle" class="handlediv"><br></div>
		<h3 class="hndle"><span><?php _e("Advanced Custom Fields v",'acf'); ?><?php echo $this->version; ?></span>
		<a class="thickbox button" href="http://localhost:8888/acf/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=advanced-custom-fields&amp;section=changelog&amp;TB_iframe=true&amp;width=640&amp;height=570"><?php _e("see what's new",'acf'); ?></a>
		</h3>
		<div class="inside">
			
		
			<table cellpadding="0" cellspacing="0" class="author">
				<tr>	
					<td style="width:24px;">
						<img src="<?php echo $this->dir ?>/images/resources.png" />
					</td>
					<td>
						<?php _e("User Guide + Code Examples",'acf'); ?> <a href="http://plugins.elliotcondon.com/advanced-custom-fields/"><?php _e("Visit the Plugin Website",'acf'); ?></a>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" class="author">
				<tr>	
					<td style="width:24px;">
						<img src="<?php echo $this->dir ?>/images/need_help.png" />
					</td>
					<td>
						<?php _e("Need Help?",'acf'); ?> <a href="http://support.plugins.elliotcondon.com/categories/advanced-custom-fields/"><?php _e("Visit the Support Forum",'acf'); ?></a>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" class="author">
				<tr>
					<td style="width:24px;">
						<img src="<?php echo $this->dir ?>/images/donate.png" />
					</td>
					<td>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="4C9N2WFW6B9QL">
						<span><?php _e("Help fund future development",'acf'); ?></span><input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_AU/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
						<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_AU/i/scr/pixel.gif" width="1" height="1">
						</form>
						<!-- Help fund future development <a href="http://www.elliotcondon.com">Donate here</a> -->
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" class="author">
				<tr>
					<td style="width:24px;">
						<img src="<?php echo $this->dir ?>/images/elliot_condon.png" />
					</td>
					<td>
						<?php _e("Created by",'acf'); ?> <a href="http://www.elliotcondon.com">Elliot Condon</a>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php endif; ?>
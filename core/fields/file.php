<?php

class acf_File
{
	var $name;
	var $title;
	
	function acf_File()
	{
		$this->name = 'file';
		$this->title = __('File','acf');
		
		add_action("admin_head-media-upload-popup", array($this, 'popup_head'));
		add_filter('media_send_to_editor', array($this, 'media_send_to_editor'), 15, 2 );
		//add_action('admin_init', array($this, 'admin_init'));
		
		add_action('admin_print_scripts', array($this, 'my_admin_scripts'));
		add_action('admin_print_styles', array($this, 'my_admin_styles'));
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * admin_print_scripts / admin_print_styles
	 *
	 * @author Elliot Condon
	 * @since 2.0.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function my_admin_scripts() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
	}
	
	function my_admin_styles() {
		wp_enqueue_style('thickbox');
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * popup_head - STYLES MEDIA THICKBOX
	 *
	 * @author Elliot Condon
	 * @since 1.1.4
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function popup_head()
	{
		if(isset($_GET["acf_type"]) && $_GET['acf_type'] == 'file')
		{
			?>
			<style type="text/css">
				#media-upload-header #sidemenu li#tab-type_url,
				#media-upload-header #sidemenu li#tab-gallery {
					display: none;
				}
				
				#media-items tr.url,
				#media-items tr.align,
				#media-items tr.image_alt,
				#media-items tr.image-size,
				#media-items tr.post_excerpt,
				#media-items tr.post_content,
				#media-items tr.image_alt p,
				#media-items table thead input.button,
				#media-items table thead img.imgedit-wait-spin,
				#media-items tr.submit a.wp-post-thumbnail {
					display: none;
				} 

				.media-item table thead img {
					border: #DFDFDF solid 1px; 
					margin-right: 10px;
				}

			</style>
			<script type="text/javascript">
			(function($){
			
				$(document).ready(function(){
				
					$('#media-items').bind('DOMNodeInserted',function(){
						$('input[value="Insert into Post"]').each(function(){
							$(this).attr('value','<?php _e("Select File",'acf'); ?>');
						});
					}).trigger('DOMNodeInserted');
					
				});
							
			})(jQuery);
			</script>
			
			<?php
		}
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * media_send_to_editor - SEND IMAGE TO ACF DIV
	 *
	 * @author Elliot Condon
	 * @since 1.1.4
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function media_send_to_editor($html, $id)
	{
		parse_str($_POST["_wp_http_referer"], $arr_postinfo);
		
		if(isset($arr_postinfo["acf_type"]) && $arr_postinfo["acf_type"] == "file")
		{

			$file_src = wp_get_attachment_url($id);
		
			?>
			<script type="text/javascript">
			
				self.parent.acf_div.find('input.value').val('<?php echo $file_src; ?>');
			 	self.parent.acf_div.find('p.file span').html('<?php echo $file_src; ?>');
			 	self.parent.acf_div.addClass('active');
			 	
			 	// reset acf_div and return false
			 	self.parent.acf_div = null;
			 	
			 	self.parent.tb_remove();
				
			</script>
			<?php
			exit;
		} 
		else 
		{
			return $html;
		}
		
	}
		
	
	function html($field)
	{
		
		$class = "";
		$file = "";
		
		if($field->value != '')
		{
			$file = $field->value;
			$class = " active";
		}

		echo '<div class="acf_file_uploader'.$class.'">';
			echo '<a href="#" class="remove_file"></a>';
			echo '<p class="file"><span>'.$file.'</span> <input type="button" class="button" value="'.__('Remove File','acf').'" /></p>';
			echo '<input class="value" type="hidden" name="'.$field->input_name.'" value="'.$field->value.'" />';
			echo '<p class="no_file">'.__('No File selected','acf').'. <input type="button" class="button" value="'.__('Add File','acf').'" /></p>';
		echo '</div>';

	}
	
}

?>
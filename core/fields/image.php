<?php

class acf_Image
{
	var $name;
	var $title;

	function acf_Image()
	{
		$this->name = 'image';
		$this->title = __('Image','acf');

		add_action('admin_head-media-upload-popup', array($this, 'popup_head'));
		add_filter('media_send_to_editor', array($this, 'media_send_to_editor'), 15, 2 );
		add_action('admin_init', array($this, 'admin_init'));
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
		if($_GET['acf_type'] == 'image')
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
				#media-items tr.submit a.wp-post-thumbnail,
				form#filter {
					display: none;
				}

				.media-item table thead img {
					border: #DFDFDF solid 1px; 
					margin-right: 10px;
				}

			</style>
			
			<?php
		}
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * rename_buttons - RENAMES MEDIA THICKBOX BUTTONS
	 *
	 * @author Elliot Condon
	 * @since 1.1.4
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function admin_init() 
	{
		if(isset($_GET["acf_type"]) && $_GET["acf_type"] == "image")
		{
			add_filter('gettext', array($this, 'rename_buttons'), 1, 3);
		}
	}
	
	function rename_buttons($translated_text, $source_text, $domain) {
		if(isset($_GET["acf_type"]) && $_GET["acf_type"] == "image") 
		{
			if ($source_text == 'Insert into Post') {
				return __('Select Image', 'acf' );
			}
		}
		return $translated_text;
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
		
		if(isset($arr_postinfo["acf_type"]) && $arr_postinfo["acf_type"] == "image")
		{

			$file_src = wp_get_attachment_url($id);
		
			?>
			<script type="text/javascript">
			
				self.parent.acf_div.find('input.value').val('<?php echo $file_src; ?>');
			 	self.parent.acf_div.find('img').attr('src','<?php echo $file_src; ?>');
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
		
		if($field->value != '')
		{
			$class = " active";
		}

		echo '<div class="acf_image_uploader'.$class.'">';
			echo '<a href="#" class="remove_image"></a>';
			echo '<img src="'.$field->value.'" alt=""/>';
			echo '<input class="value" type="hidden" name="'.$field->input_name.'" value="'.$field->value.'" />';
			echo '<p>'.__('No image selected','acf').'. <input type="button" class="button" value="'.__('Add Image','acf').'" /></p>';
		echo '</div>';

	}
	
}

?>
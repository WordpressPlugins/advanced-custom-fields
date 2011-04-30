<?php

class Image
{
	var $name;
	var $title;

	function Image()
	{
		$this->name = 'image';
		$this->title = __('Image','acf');

		add_action("admin_head-media-upload-popup", array($this, 'popup_head'));
	}
	
	
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
			<script type="text/javascript">
				(function($){
					$(document).ready(function(){
						$('input.button[value="Insert into Post"]').attr('value','<?php _e('Select Image','acf'); ?>');
					});
				})(jQuery);
			</script>
			<?php
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
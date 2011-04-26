<?php

class File
{
	var $name;
	var $title;
	
	function File()
	{
		$this->name = 'file';
		$this->title = __('File','acf');
		
		add_action("admin_head-media-upload-popup", array($this, 'popup_head'));
	}
	
	
	function popup_head()
	{
		if($_GET['acf_type'] == 'file')
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
						$('input[value="Insert into Post"]').attr('value','<?php _e('Select File','acf'); ?>');
					});
				})(jQuery);
			</script>
			<?php
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
(function($){

	// exists
	$.fn.exists = function(){return jQuery(this).length>0;}
	
	// vars
   	var wysiwyg_count = 0;
   	var post_id = 0;
   	
   	// global vars
   	window.acf_div = null;
	
	
	/*-------------------------------------------
		Wysiwyg
	-------------------------------------------*/
	$.fn.make_acf_wysiwyg = function()
	{	
		wysiwyg_count++;
		var id = 'acf_wysiwyg_'+wysiwyg_count;
		//alert(id);
		$(this).find('textarea').attr('id',id);
		tinyMCE.execCommand('mceAddControl', false, id);
	}
	
	/*-------------------------------------------
		Datepicker
	-------------------------------------------*/
	$.fn.make_acf_datepicker = function()
	{
		var format = 'dd/mm/yy';
		if($(this).siblings('input[name="date_format"]').val() != '')
		{
			format = $(this).siblings('input[name="date_format"]').val();
		}
		
		$(this).datepicker({ 
			dateFormat: format 
		});
	};
	
	/*-------------------------------------------
		Hide Meta Boxes
	-------------------------------------------*/
	$.fn.hide_meta_boxes = function()
	{
		var screen_options = $('#screen-meta');
		var div = $(this);
		
		// hide content_editor
		if(!div.find('input[name=show_the_content]').exists())
		{
			$('#postdivrich').hide();
		}
		
		// hide custom_fields
		if(!div.find('input[name=show_custom_fields]').exists())
		{
			$('#postcustom').hide();
			screen_options.find('label[for=postcustom-hide]').hide();
		}
		
		// hide discussion
		if(!div.find('input[name=show_discussion]').exists())
		{
			$('#commentstatusdiv').hide();
			screen_options.find('label[for=commentstatusdiv-hide]').hide();
		}
		
		// hide comments
		if(!div.find('input[name=show_comments]').exists())
		{
			$('#commentsdiv').hide();
			screen_options.find('label[for=commentsdiv-hide]').hide();
		}
		
		// hide slug
		if(!div.find('input[name=show_slug]').exists())
		{
			$('#slugdiv').hide();
			screen_options.find('label[for=slugdiv-hide]').hide();
		}
		
		// hide author
		if(!div.find('input[name=show_author]').exists())
		{
			$('#authordiv').hide();
			screen_options.find('label[for=authordiv-hide]').hide();
		}
		
		screen_options.find('label[for=acf_input-hide]').hide();
	}
	
	/*-------------------------------------------
		Image Upload
	-------------------------------------------*/
	$.fn.make_acf_image = function(){
	
		var div = $(this);
		
		div.find('input.button').click(function(){
			
			// set global var
			window.acf_div = div;
			
			
			// show the thickbox
		 	tb_show('Add Image to field', 'media-upload.php?post_id='+post_id+'&type=image&acf_type=image&TB_iframe=1');
		 	
		 				
		 	return false;
		});
		
		
		div.find('a.remove_image').unbind('click').click(function()
		{
			div.find('input.value').val('');
			div.removeClass('active');
		
			return false;
		});
	}
	
	
	/*-------------------------------------------
		File Upload
	-------------------------------------------*/
	$.fn.make_acf_file = function(){
	
		var div = $(this);

		
		div.find('p.no_file input.button').click(function(){
			
			// set global var
			window.acf_div = div;
			
			
			// show the thickbox
		 	tb_show('Add File to field', 'media-upload.php?post_id='+post_id+'&type=file&acf_type=file&TB_iframe=1');
		 	
		 	
			return false;
		});
		
		
		
		div.find('p.file input.button').unbind('click').click(function()
		{
			div.find('input.value').val('');
			div.removeClass('active');
		
			return false;
		});
	}

	
	
	
	$.fn.make_all_fields = function()
	{
		var div = $(this);
		
		// wysiwyg
		div.find('.acf_wysiwyg').each(function(){
			$(this).make_acf_wysiwyg();	
		});
		
		// datepicker
		div.find('.acf_datepicker').each(function(){
			$(this).make_acf_datepicker();
		});
		
		// image
		div.find('.acf_image_uploader').each(function(){
			$(this).make_acf_image();
		});
		
		// file
		div.find('.acf_file_uploader').each(function(){
			$(this).make_acf_file();
		});
	}
	
	
	/*-------------------------------------------
		Document Ready
	-------------------------------------------*/
	$(document).ready(function(){
		
		post_id = $('form#post input#post_ID').val();
		var div = $('.postbox#acf_input');
		
		
		if(typeof(tinyMCE) != "undefined")
		{
			tinyMCE.settings.theme_advanced_buttons1 += ",|,add_image,add_video,add_audio,add_media";
			tinyMCE.settings.theme_advanced_buttons2 += ",code";
		}
		
	
		div.hide_meta_boxes();
		div.make_all_fields();
		
	});
	
})(jQuery);

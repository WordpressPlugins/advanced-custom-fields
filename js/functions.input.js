jQuery(document).ready(function($){
   	
   	$.fn.exists = function(){return jQuery(this).length>0;}
   	
   	// elements
   	var div = $('.postbox#acf_input');
	var table = div.find('table#acf_input');
	
	
	// add code to tinymce
	tinyMCE.settings.theme_advanced_buttons1 += ",|,add_image,add_video,add_audio,add_media";
	tinyMCE.settings.theme_advanced_buttons2 += ",code";
	
	
	// create wysiwyg's
	table.find('.acf_wysiwyg textarea').each(function(i)
	{
		// make i start from 1 to match row number
		var id = 'acf_wysiwyg_'+(i+1);
		$(this).attr('id',id);
	
		tinyMCE.execCommand('mceAddControl', false, id);
	
	});
	
	
	// hide meta boxes
	
	var screen_options = $('#screen-meta');
	
	// hide content_editor
	if(!div.find('input[name=show_content_editor]').exists())
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
	
	
	// images iframe
	
	function setup_iframes()
	{
	
	table.find('.acf_image_uploader').each(function(){
		
		var div = $(this);
		var iframe = div.find('iframe');

		iframe.contents().find('input#acf_image').unbind('change').change(function(){
			
			// set up load event
			iframe.unbind("load").load(function(){
			
				var result = $(this).contents().find('body .result').html();
			
				if(result == null)
				{
					//alert('null');
				}
				else if(result == '0')
				{
					//alert('0');
					//window.history.back();
				}
				else
				{
					//alert(result);
					div.children('input[type=hidden]').attr('value',result);
					
					div.append('<img src="'+result+'" width="100" height="100" />');
					div.find('img').hide().load(function(){
						$(this).fadeIn(500);
						div.children('a.remove_image').removeClass('hide');
					});
					//iframe.history.back();
					div.find('iframe').addClass('hide');
				}
				
				div.find('.loading').remove();
				setup_iframes();
				
			});
			
			// send image
			iframe.contents().find('form').submit();
			
			// add loading div
			div.append('<div class="loading"></div>');
		});
		
		div.find('a.remove_image').unbind('click').click(function()
		{
			div.find('input[type=hidden]').val('');
			div.find('img').remove();
			div.find('iframe').removeClass('hide');
			$(this).addClass('hide');
		
			return false;
		});
	});
	
	}
	
	setup_iframes();
	
	
   
});

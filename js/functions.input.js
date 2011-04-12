(function($){

	// exists
	$.fn.exists = function(){return jQuery(this).length>0;}
	
	// vars
   	var wysiwyg_count = 0;
   	
	// add code to tinymce
	
	
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
		var iframe = div.find('iframe');
		
		
		// can only add functions to the iframe if its loaded
		iframe.load(function(){
		
			iframe.contents().find('form').unbind('submit').submit(function(){
				
				// if value is empty, return faslse and stop it from uploading!
				if(iframe.contents().find('input[type="file"]').val() == '')
				{
					alert('No Image chosen');
					return false;
				}
				
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
					
				});
				
				// send image
				//iframe.contents().find('form').submit();
				
				// add loading div
				div.append('<div class="loading"></div>');
				
				return true;
			});
			// end form.submit
			
			div.find('a.remove_image').unbind('click').click(function()
			{
				div.find('input[type=hidden]').val('');
				div.find('img').remove();
				div.find('iframe').removeClass('hide');
				$(this).addClass('hide');
			
				return false;
			});
			// end a.remove_image.click
			
		}).trigger('load');
		// end iframe.load
		
	}
	
	/*-------------------------------------------
		File Upload
	-------------------------------------------*/
	$.fn.make_acf_file = function(){
		var div = $(this);
		var iframe = div.find('iframe');
		
		// can only add functions to the iframe if its loaded
		iframe.load(function(){
		
			iframe.contents().find('form').unbind('submit').submit(function(){
				
				// if value is empty, return faslse and stop it from uploading!
				if(iframe.contents().find('input[type="file"]').val() == '')
				{
					alert('No File chosen');
					return false;
				}

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
						
						div.append('<span>'+result+'</span>');
						div.children('a.remove_file').removeClass('hide');
	
						//iframe.history.back();
						div.find('iframe').addClass('hide');
					}
					
					div.find('.loading').remove();
					
				});
				
				// send image
				//iframe.contents().find('form').submit();
				
				// add loading div
				div.append('<div class="loading"></div>');
				
				return true;
			});
			
			div.find('a.remove_file').unbind('click').click(function()
			{
				div.find('input[type=hidden]').val('');
				div.find('span').remove();
				div.find('iframe').removeClass('hide');
				$(this).addClass('hide');
			
				return false;
			});
			// end a.remove_file.click
			
		}).trigger('load');
		// end iframe.load
	}
	
	/*-------------------------------------------
		Repeaters
	-------------------------------------------*/
	$.fn.make_acf_repeater = function(){
		
		// vars
		var div = $(this);
		var add_field = div.find('a#add_field');
		var fields_limit = 99;
		
		
		/*-------------------------------------------
			Add Field Button
		-------------------------------------------*/
		add_field.unbind("click").click(function(){
			
			// limit fields
			if(div.children('table').children('tbody').children('tr').length >= fields_limit)
			{
				alert('Field limit reached!');
				return false;
			}
			
			// clone last tr
			var new_field = div.children('table').children('tbody').children('tr').last().clone();
			
			// append to table
			div.children('table').children('tbody').append(new_field);
			
			// set new field
			new_field.reset_values();
			
			// re make special fields
			new_field.make_all_fields();
						
			// update order numbers
			update_order_numbers();
			
			
			return false;
			
			
		});
	}
	
	
	/*-------------------------------------------
		Update Order Numbers
	-------------------------------------------*/
	function update_order_numbers(){
		$('.postbox#acf_input .repeater').each(function(){
			$(this).children('table').children('tbody').children('tr').each(function(i){
				$(this).children('td.order').html(i+1);
			});
	
		});
	}
	
	/*-------------------------------------------
		Sortable
	-------------------------------------------*/
	$.fn.make_sortable = function(){
		
		//alert('make sortable');
		var div = $(this).find('.repeater');
		
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};
		
		div.find('table.acf_input').children('tbody').unbind('sortable').sortable({
			update: function(event, ui){
				update_order_numbers();
				$(this).make_all_fields();
				//alert('update');
				},
			handle: 'td.order',
			helper: fixHelper,
			//pre process stuff as soon as the element has been lifted
		    start: function(event, ui)
		    {
		    	console.log(ui.item);
		    	if(ui.item.find('.acf_wysiwyg').exists())
		    	{
		    		//console.log('aaaah, i found a wysiwyg')
		    		var id = ui.item.find('.acf_wysiwyg textarea').attr('id');
		    		//alert(tinyMCE.get(id).getContent());
		    		//tinyMCE.execCommand("mceRemoveControl", false, id);
		    	}
		    },
		
		    //post process stuff as soon as the element has been dropped
		    stop: function(event, ui)
		    {
		    	if(ui.item.find('.acf_wysiwyg').exists())
		    	{
		    		//var id = ui.item.find('.acf_wysiwyg textarea').attr('id');
		    		//tinyMCE.execCommand("mceAddControl", false, id);
		    		//div.make_sortable();
		    	}
		    }
		});
	}
	
	
	
	/*-------------------------------------------
		Reset Values
	-------------------------------------------*/
	$.fn.reset_values = function(){
	
		if($(this).find('.acf_wysiwyg').exists())
		{
			var wysiwyg = $(this).find('.acf_wysiwyg');
			
			var name = wysiwyg.find('textarea').first().attr('name');
			
			wysiwyg.html('<textarea name="'+name+'"></textarea>');
		}
		
		
		// image upload
		$(this).find('img').remove();
		$(this).find('a.remove_image').addClass('hide');
		$(this).find('iframe').removeClass('hide');
		

		// total fields
		var total_fields = $(this).siblings('tr').length;

		
		// reset all values
		$(this).find('[name]').each(function()
		{
			var name = $(this).attr('name').replace('['+(total_fields-1)+']','['+(total_fields)+']');
			$(this).attr('name', name);
			$(this).val('');
			$(this).attr('checked','');
			$(this).attr('selected','');
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
		Remove Field Button
	-------------------------------------------*/
	$.fn.add_remove_buttons = function(){
		$(this).find('a.remove_field').unbind('click').live('click', function(){
			
			var total_fields = $(this).parents('.repeater').children('table').children('tbody').children('tr').length;
			
			// needs at least one
			if(total_fields <= 1)
			{
				return false;
			}
			
			var tr = $(this).parents('tr').first();
			console.log('s');
			tr.fadeTo(300,0,function(){
				tr.animate({'height':0}, 300, function(){
					tr.remove();
					update_order_numbers();
				});
			});
			
			return false;
			
		});
	}
	
	
	
	
	/*-------------------------------------------
		Document Ready
	-------------------------------------------*/
	$(document).ready(function(){
		
		var div = $('.postbox#acf_input');
		
		tinyMCE.settings.theme_advanced_buttons1 += ",|,add_image,add_video,add_audio,add_media";
		tinyMCE.settings.theme_advanced_buttons2 += ",code";
	
		// hide meta boxes
		div.hide_meta_boxes();
		
		div.make_all_fields();
		
		div.make_sortable();
		
		div.add_remove_buttons();
		
		// repeater
		div.find('.repeater').each(function(){
			$(this).make_acf_repeater();
		});
		
		
	});
	
})(jQuery);

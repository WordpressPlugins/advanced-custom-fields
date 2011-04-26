(function($){
	// exists
	$.fn.exists = function(){return jQuery(this).length>0;}

	
	$.fn.make_acf = function()
	{
		// vars
		var fields = $(this); // .fields
		var add_field = fields.siblings('.table_footer').children('a#add_field');
		var fields_limit = parseInt(fields.siblings('input[name=fields_limit]').val()) - 1;
				
		/*-------------------------------------------
			Add Field Button
		-------------------------------------------*/
		add_field.unbind("click").click(function(){
			
			var total_fields = fields.children('.field').length-1;
			
			// limit fields
			if(total_fields >= fields_limit)
			{
				alert('Field limit reached!');
				return false;
			}
			
			// clone last tr
			var new_field = fields.children('.field').last().clone(true);
			
			// reset new field
			new_field.reset_values();
			new_field.update_names(total_fields, (total_fields+1));
			
			// append to table
			fields.append(new_field);
			
			// close options
			new_field.find('select.type').trigger('change');
			new_field.find('input.label').focus();
			
			// update order numbers
			fields.update_order_numbers();
		
			return false;
			
			
		});
		
		
		/*-------------------------------------------
			Sortable
		-------------------------------------------*/
		fields.sortable({
			update: function(event, ui){fields.update_order_numbers();},
			handle: 'table'
		});
		
		
		/*-------------------------------------------
			Auto Fill Name
		-------------------------------------------*/
		fields.children('.field').children('table').find('input.label').unbind('blur').blur(function()
		{
			var label = $(this);
			var name = $(this).parent().siblings('td.name').children('input');
			
			if(name.val() == '')
			{
				var val = label.val().toLowerCase().split(' ').join('_').split('\'').join('');
				name.val(val);
			}
		});
		
		
		/*-------------------------------------------
			Remove Field Button
		-------------------------------------------*/
		fields.children('.field').children('table').find('a.remove_field').unbind('click').click(function()
		{	
			// needs at least one
			if(fields.children('.field').length <= 1)
			{
				return false;
			}
			
			var field = $(this).parents('.field').first();
			field.fadeTo(300,0,function(){
				field.animate({'height':0}, 300, function(){
					field.remove();
					fields.update_order_numbers();
				});
			});
			
			return false;
		});
		
		
		/*-------------------------------------------
			Field Options
		-------------------------------------------*/
		fields.children('.field').children('table').find('select.type').change(function()
		{
			var selected = $(this).val();
			var td = $(this).parent('td');
			
			var field = $(this).parents('.field').first();
			var field_options = field.children('.field_options');
			var selected_option = field_options.children('.field_option#'+selected);
			
			// remove preivous field option button
			td.children('a.field_options_button').remove();
			field.removeClass('options_open');
			field_options.children('.field_option').hide();
			field_options.children('.field_option').find('[name]').attr('disabled', 'true');
			
			
			// if options...
			if(selected_option.exists())
			{
				var a = $('<a class="field_options_button" href="javascript:;"></a>');
				td.append(a);
				
				// all options are disabled, make the chosen one abled!
				selected_option.find('[name]').removeAttr('disabled');
				
				a.click(function(){
					if(!field.is('.options_open'))
					{
						field.addClass('options_open');
						selected_option.animate({'height':'toggle', 'padding-top':'toggle', 'padding-bottom':'toggle'}, 500);
					}
					else
					{
						selected_option.animate({'height':'toggle', 'padding-top':'toggle', 'padding-bottom':'toggle'}, 500, function(){
							field.removeClass('options_open');
						});
					}
					
					selected_option.find('[name]').removeAttr('disabled');
				});
				
			}
		}).trigger('change');
		
		
	}
	
	/*-------------------------------------------
		Reset Values
	-------------------------------------------*/
	$.fn.reset_values = function(){
		
		$(this).find('input[type="text"]').val('');
		$(this).find('input[type="hidden"]').val('');
		$(this).find('textarea').val('');
		$(this).find('select option').removeAttr('selected');
		$(this).find('select[multiple="multiple"] option').attr('selected','selected');
		$(this).find('input[type="checkbox"]').removeAttr('checked');
	}
	
	$.fn.update_names = function(old_no, new_no)
	{
		//alert('passed through '+total_fields);
		$(this).find('[name]').each(function()
		{
			var name = $(this).attr('name').replace('['+(old_no)+']','['+new_no+']');
			$(this).attr('name', name);
		});
	}
	
	
	/*-------------------------------------------
		Update Orer Numbers
	-------------------------------------------*/
	$.fn.update_order_numbers = function(){
		$(this).children('.field').each(function(i){
			$(this).children('table').children('tbody').children('tr').children('td.order').html(i+1);
		});
	}
	

	/*-------------------------------------------
		Document Ready
	-------------------------------------------*/
	$(document).ready(function(){
   		$('div.postbox#acf_fields .fields').each(function(){
   			$(this).make_acf();
   		});
   		
   		$('input#publish').click(function(){
   			$('div.postbox#acf_fields select.type').each(function(){
   				if($(this).val() == 'null')
   				{
   					alert('** All fields require a type selected **');
   				}
   			});
   			
   		});
   		
   		$('div.postbox a.help').click(function(){
   			$('div.postbox .help_box_mask').animate({'height':'toggle'}, 500);
   		});   		
	});

})(jQuery);

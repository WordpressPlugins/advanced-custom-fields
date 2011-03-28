jQuery(document).ready(function($){
   	
   	// exists
   	$.fn.exists = function(){return jQuery(this).length>0;}
   	
   	
   	// elements
   	var div = $('div.postbox#acf_fields');
   	var fields = div.find('.fields');


	// vars
	var total_fields = parseInt(div.find('input[name=total_fields]').attr('value')) - 1;
	var fields_limit = parseInt(div.find('input[name=fields_limit]').attr('value')) -1;
	

	div.find('a#add_field').unbind("click").click(function(){
		
		// limit fields
		if(total_fields >= fields_limit)
		{
			alert('Field limit reached!');
			return false;
		}
		
		// increase total fields
		total_fields++;

		// clone last tr
		var new_field = fields.find('.field:last-child').clone(true);
		
		// update names of input, textarea and all other elements that have name
		new_field.find('[name]').each(function()
		{
			var name = $(this).attr('name').replace('[fields]['+(total_fields-1)+']','[fields]['+(total_fields)+']');
			$(this).attr('name', name);
			$(this).val('');
			$(this).attr('checked','');
			$(this).attr('selected','');
		});
		
		// append to table
		fields.append(new_field);
		
		new_field.find('select.type').trigger('change');
		new_field.find('input.label').focus();
		
		// update order numbers
		update_order_numbers();
		
		return false;
	});
	
	// update order numbers
	function update_order_numbers(){
		fields.find('.field').each(function(i){
			$(this).find('td.order').html(i+1);
		});
	}
	
	// sortable tr
	fields.sortable({
		update: function(event, ui){update_order_numbers();},
		handle: 'table'
	});
	
	
	// add default names
	fields.find('.field').each(function(){
		
		var _this = $(this);
		
		// auto complete name
		_this.find('input.name').unbind('focus').focus(function()
		{
			var _this = $(this).parents('.field');
			if($(this).val() == "")
			{
				var label = _this.find('input.label').val();
				label = label.toLowerCase().split(' ').join('_').split('\'').join('');
				$(this).val(label);
			}
		});
		
		// add remove button functionality
		_this.find('a.remove_field').unbind('click').click(function()
		{	
			if(fields.find('.field').length <= 1)
			{
				return false;
			}
			
			var _this = $(this).parents('.field');
			_this.fadeTo(300,0,function(){
				_this.animate({'height':0}, 300, function(){
					_this.remove();
					update_order_numbers();
				});
			});
			
			return false;
		});
		
		// show options for type
		_this.find('select.type').change(function()
		{
			var _this = $(this).parents('.field');
			
			// store selected value
			var selected = $(this).val();
			var td = $(this).parent();
			
			// remove preivous field option button
			td.find('a.field_options_button').remove();
			_this.removeClass('options_open');
			_this.find('div.field_options div.field_option').hide();
			_this.find('div.field_options div.field_option [name]').attr('disabled', true);
			
			// if options...
			if(_this.find('div.field_options').find('div.field_option#'+selected).exists())
			{
				var a = $('<a class="field_options_button" href="javascript:;"></a>');
				td.append(a);
				
				a.click(function(){
					if(!$(this).parents('.field').is('.options_open'))
					{
						$(this).parents('.field').addClass('options_open');
						$(this).parents('.field').find('div.field_options div.field_option#'+selected).animate({'height':'toggle'}, 500);
					}
					else
					{
						$(this).parents('.field').find('div.field_options div.field_option#'+selected).animate({'height':'toggle'}, 500, function(){
							$(this).parents('.field').removeClass('options_open');
						});
					}
					
					$(this).parents('.field').find('div.field_options div.field_option#'+selected+' [name]').removeAttr('disabled');
				});
				/*var inline_div = td.find('div.field_option#'+selected);
				
				
				
				a.click(function(){
				
					inline_div.attr('id','acf_inline_option');
					
					$.fancybox({
						padding		: 0,
						type				: 'inline',
						href				: '#acf_inline_option',
				        autoDimensions		: true,
						overlayColor		: '#000',
						onClosed			: function(){
							inline_div.attr('id',selected);
						}
					});
					
				});*/
				
			}
			
			

			
			//$(this).parents('tr').find('.field_options .field_option').removeClass('open').find('[name]').attr('disabled', true);
			//$(this).parents('tr').find('.field_options .field_option#'+selected).addClass('open').find('[name]').removeAttr('disabled');

		}).trigger('change');

		
	});
	
	
   
});

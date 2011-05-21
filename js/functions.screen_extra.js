(function($){
	
	$.fn.exists = function()
	{
		return jQuery(this).length>0;
	};
	
	$(document).ready(function(){
		
		// add new buttons to screen meta
		$('#contextual-help-link-wrap').each(function(){
			$(this).hide();
		});
		$('.screen-meta-toggle.acf').each(function(){
			$('#screen-meta-links').append($(this));
		});
	
		$('.screen-meta-wrap.acf').each(function() {
			$('#screen-meta-links').before($(this));
		});
		
		$('#screen-meta-links a#screen-meta-activate-acf-link').unbind('click').click(function() {
			var a = $(this);
			$(a.attr('href')+'-wrap').slideToggle('fast', function() {
				if (a.hasClass('screen-meta-shown')) {
					a.css({'background-position':'right top'}).removeClass('screen-meta-shown');
					$('.screen-meta-toggle').css('visibility', 'visible');
				}
				else {
					$('.screen-meta-toggle').css('visibility', 'hidden');
					a.css({'background-position':'right bottom'}).addClass('screen-meta-shown').parent().css('visibility', 'visible');
				}
			});
			return false;
		});
		
		
		$('.acf_col_right').each(function(){
		
			$('.wrap').wrapInner('<div class="acf_col_left" />');
			$('.wrap').wrapInner('<div class="acf_cols" />');
			$(this).removeClass('hidden').prependTo('.acf_cols');

		});
		
				
	});

})(jQuery);
(function($) {                                         
$.fn.tab = function(o) {
    o = $.extend({
    	crt:0
    }, o || {});

    return this.each(function() {
    	var $tab = $(this);
    	$tab.find('.tab-h').find('li').eq(o.crt).addClass('tab-h-c');
    	$tab.find('.tab-c').children('div').hide();
    	$tab.find('.tab-c>div:eq('+o.crt+')').show();
		$tab.find('.tab-h').find('li').hover(function(){
			var i = $(this).index(); 
			$(this).siblings().removeClass('tab-h-c');
			$(this).addClass('tab-h-c');
			$tab.find('.tab-c').children('div').hide();
			$tab.find('.tab-c').children('div').eq(i).show();
		});
    });
};

})(jQuery);
;(function($) {
    var is_ie = (navigator.userAgent.toLowerCase().indexOf('msie') != -1);
    $(function($) {
    	$(".aridoc-container").each(function() {
		if (!is_ie) {
	    		$(this).find("IFRAME").bind("load", function() {
	    			$(this).closest(".aridoc-container").removeClass("aridoc-loading");
	    		});
		} else {
	    		var self = $(this);
	    		$(this).find("IFRAME").ready(function() {
	    			self.find("IFRAME").closest(".aridoc-container").removeClass("aridoc-loading");
	    		});
		}
    	});
    });
})(jQuery);
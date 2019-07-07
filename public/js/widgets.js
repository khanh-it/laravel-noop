/**
 *
 */
(function(g) {
	var _adsWidgetsInit = g;
	if (typeof _adsWidgetsInit !== 'object') {
		console.warn('_adsWidgetsInit is missing!');
		return;
	}
	var onReady = function($) {
		var $ads = $(_adsWidgetsInit);
		//
		var $ifr = $('<iframe />')
			.attr({
				"src": "http://127.0.0.1:8080/html/iframe.html"
			})
			.css({
				"width": 300,
				"height": 200,
			})
			.appendTo($ads)
		;
	};
	// Init + execute
	if (typeof jQuery !== 'undefined') {
		onReady(jQuery);
	} else {
		var s = document.createElement('script');
		s.src = 'https://code.jquery.com/jquery-1.12.4.min.js';
		s.crossorigin = "anonymous";
		s.type = 'text/javascript';
		s.onload = function() {
			onReady(jQuery);
		};
		(document.getElementsByTagName('head') || [document.body])[0].appendChild(s);
	}
})(window);
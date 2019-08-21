{{--
 *
 *
--}}
<!--script--><script>
(function(g) {
  function f($) {
	var hash = encodeURIComponent("{{$hash}}"), host = "https://{{$host}}/", $ads = null;
	var css = {"border": "none 0", "outline": "none 0", "overflow": "hidden", "max-width": "100%", "max-height": "100%"};
    try {
      $ads = $(g._adsWidgetsAsyncInit || "#{{$adsId}}").css($.extend({}, css, {
        "width": {{1 * $adsEnt->ads_spec_width}},
        "height": {{1 * $adsEnt->ads_spec_height}}
      }));
    } catch (e) {}
    if (!$ads || ($ads && !$ads.length)) {
      return console.warn('#ads is missing!');
    }
    var $ifr = $('<iframe/>').appendTo($ads)
    .attr({"scrolling": "no", "src": host + "resources/html/ads_frame.html?" + hash + '&_fr=' + location})
    .css($.extend({}, css, { "width": "100%", "height": "100%" }));
  }
  // Init + execute
  if (typeof jQuery !== 'undefined') {
    f(jQuery);
  } else {
    var s = document.createElement('script');
    s.src = 'https://code.jquery.com/jquery-1.12.4.min.js';
    s.crossorigin = "anonymous"; s.type = 'text/javascript'; s.async = true; s.defer = true;
    s.onload = function() { f(jQuery); };
    (document.getElementsByTagName('head') || [document.body])[0].appendChild(s);
  }
})(window);
</script><!--/script-->

{{--
 *
 *
--}}
<!--script--><script>
(function(g) {
  var hash = encodeURIComponent("{{$hash}}"),
    host = ("https://{{$host}}/"),
    $ads = null
  ;
  function f($) {
    try {
      $ads = $(g._adsWidgetsAsyncInit);
    } catch (e) {}
    if (!$ads || ($ads && !$ads.length)) {
      return console.warn('#ads is missing!');
    }
    var $ifr = $('<iframe/>')
    .attr("src", host + "resources/html/ads_frame.html?" + hash)
    .css({
      "border": "none 0", "outline": "none 0",
      "overflow": "hidden",
      "max-width": "100%", "width": {{1 * $data->ads_spec_width}},
      "max-height": "100%", "height": {{1 * $data->ads_spec_height}}
    })
    .appendTo($ads);
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

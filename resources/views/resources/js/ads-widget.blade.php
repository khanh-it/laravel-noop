{{--
 *
 *
 *
--}}
<!--script--><script>
/**
 *
 */
(function(glob) {
  //
  var _adsWidgetsAsyncInit = glob._adsWidgetsAsyncInit;
  if (typeof _adsWidgetsAsyncInit === 'undefined') {
    console.warn('_adsWidgetsAsyncInit is missing!');
    return;
  }
  //
  var hash = encodeURIComponent("{{$hash}}");
  var host = ("https://{{$host}}/");
  //
  function onReady($) {
    var $ads = $(_adsWidgetsAsyncInit);
    //
    var $ifr = $('<iframe />').attr({
      "src": host + "resources/html/ads_frame.html?" + hash,
    })
    .css({ "width": 300, "height": 200 })
    .appendTo($ads);
  }
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
</script><!--/script-->

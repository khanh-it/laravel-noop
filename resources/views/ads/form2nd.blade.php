@php
/**
 *
 */
$host = \Request::getFacadeRoot()->getHost();
@endphp

@php /* html structure */ \ob_start(); @endphp
<!doctype html>
<html>
  <head></head>
  <body>
    ...
    <!-- div place holder for ads -->
    __div__
    ...
    <!-- Embedded script goes here -->
    __script__
  </body>
</html>
@php $html = \htmlentities(\trim(\ob_get_clean())); @endphp

@php /* script structure */ \ob_start(); @endphp
<script type="text/javascript">
(function(d){
	var s = d.createElement('script');
	s.src = 'https://{{$host}}/resources/js/widget-ads.js?__adshash__;_ads-__adsid__';
	s.type = "text/javascript"; s.async = true; s.defer = true;
	(d.getElementsByTagName('head') || [d.body])[0].appendChild(s);
})(document);
</script>
@php $script = \trim(\ob_get_clean()); @endphp

@php
//
//
$html = str_replace(
    [ '__div__', '__script__', ],
    [
        '<pre style="font-weight:bold;color:green;">'
            . \htmlentities('<div id="_ads-__adsid__"></div>')
        . '</pre>',
        '<pre style="font-weight:bold;color:chocolate;">'
            . \htmlentities($script)
        . '</pre>',
    ],
    \nl2br($html)
);
@endphp

{!! Form::open([
    'id' => 'form2nd',
    'class' => 'form-horizontal targetform',
    'onsubmit' => 'return false;',
]) !!}
    {!! $html !!}
{!! Form::close() !!}

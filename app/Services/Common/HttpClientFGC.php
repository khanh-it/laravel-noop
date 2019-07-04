<?php
namespace App\Services\Common;

/**
 * Lib http client
 * @author KhanhDTP 2018-05-09
 */
class HttpClientFGC
{
    /**
     * Debug?
     * @param bool $debug Debug flag?
     * @return void
     */
    public static $debug = false;

    /**
     * Parse response headers
     * @param array $headers
     * @return void
     */
    public static function parseHeaderResponseCode($headers)
    {
        $responseCode = null;
        foreach ((array)$headers as $k => $v) {
            if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out)) {
                $responseCode = intval($out[1]);
                break;
            }
        }
        return $responseCode;
    }

    /**
     * Get header Host string from url string
     * @param string $url API url
     * @return string
     */
    public static function getHdrHost($url)
    {
        if (preg_match('/https?:\/\/([^\/]+)\/?.*/i', $url, $hHost)) {
            $hHost = $hHost[1];
        }
        // Return
        return $hHost;
    }

    /**
     * Get header Date string
     * @return string
     */
    public static function getHdrDate()
    {
        // Get system's default timezone.
        $tz = strtoupper(date_default_timezone_get());
        // Change default_timezone
        date_default_timezone_set($tzNew = 'ASIA/TOKYO');
        // Get header Date
        $date = date(\DateTime::RFC1123, time());
        // Restore default_timezone
        if ($tz != $tzNew) {
            date_default_timezone_set($tz);
        }
        // Return
        return $date;
    }

    /**
     * Http make request!
     * @param string $url Http request url
     * @param array $options An array of options
     * @return array
     */
    public function call($url, $options = array())
    {
        // Get, format options
        $options = \array_replace($options, array(
            ($prop = 'http_content') => ($options[$prop] ?? null),
            ($prop = 'http_content_encoding') => ($options[$prop] ?? null),
            ($prop = 'http_method') => ($options[$prop] ?? null),
            ($prop = 'http_header') => ($options[$prop] ?? null),
            ($prop = 'scontext_http_timeout') => ($options[$prop] ?? null),
            ($prop = 'scontext_ssl_verify_peer') => ($options[$prop] ?? null),
            ($prop = 'scontext_ssl_verify_peer_name') => ($options[$prop] ?? null),
            // ($prop = 'scontext_http_protocol_version') => ($options[$prop] ?? null),
            ($prop = 'scontext_ignore_errors') => ($options[$prop] ?? true),
        ));

        // Init
        $response = array(
            'url' => '',
            'response_body' => '',
            'response_header' => array(),
            'stream_context' => array(),
            'response_code' => null
        );
        // Format data
        // +++ http_content
        $content = (array)$options['http_content'];
        $contentEncoding = function_exists($options['http_content_encoding'])
            ? $options['http_content_encoding'] : 'http_build_query'
        ;
        // +++ method
        $options['http_method'] = strtoupper($options['http_method']);
        $method = empty($options['http_method']) ? 'GET' : $options['http_method'];
        $isMethodGET = ('GET' === $method);
        if ($isMethodGET) {
            $url = $url . (empty($content) ? '' : ('?' . http_build_query($content)));
            $content = '';
        } else {
            $content = $contentEncoding($content);
        }
        // +++ headers
        $httpHeader = array_merge(
            array(
                'Host: ' . static::getHdrHost($url),
                'Date: ' . static::getHdrDate(),
                'Content-Type: ' . (!$isMethodGET ? 'application/x-www-form-urlencoded; charset=UTF-8' : 'text/html'),
                'Content-Length: ' . strlen($content)
            ),
            (array)$options['http_header']
        );
        $headers = array();
        $hdrKeys = array();
        foreach ($httpHeader as $idx => $hdr) {
			$hReplace = null;
            if (is_array($hdr)) {
                $hReplace = isset($hdr[1]) ? $hdr[1] : null;
                $hdr = $hdr[0];
            }
            list($hdrKey, $hdrVal) = explode(':', $hdr);
            $hdrKey = strtolower(trim($hdrKey));
            $hdrIdx = count($headers);
            // Case header exists --> replace
            if (!is_null(isset($hdrKeys[$hdrKey]) ? $hdrKeys[$hdrKey] : null) && !(false === $hReplace)) {
                $hdrIdx = $hdrKeys[$hdrKey];
            }
            $hdrKeys[$hdrKey] = $idx;
            $headers[$hdrIdx] = $hdr;
            // Case: remove header item?
            $hdrVal = trim($hdrVal);
            if ('' === $hdrVal) {
                unset($headers[$hdrIdx]);
            }
        }
        unset($httpHeader, $hdrKeys, $hdrKey, $hdrVal, $idx, $hdr);
        // var_dump($headers); die();

        // Stream context options
        $sContext = stream_context_create($response['stream_context'] = array(
            'http' => array(
                'method'  => $method,
                'header'  => implode("\r\n", $headers), // $headers,
                'content' => $content,
                'timeout' => $options['scontext_http_timeout'] ?: (@ini_get('max_execution_time') ?: 60),
                // 'protocol_version' => $options['scontext_http_protocol_version'],
                //'request_fulluri' => true,
                //'follow_location' => 0,
                'ignore_errors' => $options['scontext_ignore_errors'],
            ),
            'ssl' => array(
                'verify_peer' => (true === $options['scontext_ssl_verify_peer']) ? true : false,
                'verify_peer_name' => (true === $options['scontext_ssl_verify_peer_name']) ? true : false,
            )
        ));

        // Make request
        ob_start();
        $response['response_body'] = @file_get_contents($response['url'] = $url, false, $sContext);
        $output = trim(ob_get_clean());
        $response['response_body'] = $response['response_body'] ?: $output;
        $response['response_code'] = static::parseHeaderResponseCode(
            $response['response_header'] = (array)(isset($http_response_header) ? $http_response_header : null)
        );

        // Return
        return $response;
    }

    /**
     * GET request
     * @param string $url Url string to make request
     * @param array $options An array of options
     * @return void
     */
    public function get($url, array $options = array())
    {
        // Format
        $options = array_replace_recursive($options, array(
            'http_method' => 'GET'
        ));
        return static::call($url, $options);
    }

    /**
     * POST request
     * @param string $url Url string to make request
     * @param array $options An array of options
     * @return void
     */
    public function post($url, array $options = array())
    {
        // Format
        $options = array_replace_recursive($options, array(
            'http_method' => 'POST'
        ));
        return static::call($url, $options);
    }
}

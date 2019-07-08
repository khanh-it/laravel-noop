<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Controllers\Controller;

/**
 * @class ResourcesController
 */
class ResourcesController extends Controller
{
    /**
     * @var string
     */
    protected $_hash = null;

    /**
     * @var App\Models\Ads
     */
    protected $_adsEnt = null;

	/**
	 * construct
	 */
	public function __construct(Request $request)
	{
        // dd($hash = Models\Ads::encryptPriKey(1), \urlencode($hash));
        // Auth
        $this->_auth($request);
    }

    /**
     * Simple auth
     * @param Request $request
     * @return void|Response
     */
    protected function _auth(Request $request)
    {
        $this->_hash = \current($request->keys());
        $id = Models\Ads::decryptPriKey($this->_hash);
        $this->_adsEnt = Models\Ads::find4Resource($id);
        if (!$this->_adsEnt) {
            \header("HTTP/1.0 404 Not Found");
            exit(0);
        }
    }

    /**
     * Response js
     * @param string $view
     * @param array $data
     * @param int $code
     * @return void|Response
     */
    protected function _resJs($view, $data, $code = 200)
    {
        // Get, format view
        $js = view($view, $data);
        $js = trim(\str_replace(
            [ '<!--script--><script>', '</script><!--/script-->' ],
            [ '', '' ],
            trim($js)
        ));

        return response($js, $code)
            ->header('Content-Type', 'application/json')
        ;
    }

    /**
     * @TODO:...
     */
    protected function _adsStat($_rdr, array $opts = [])
    {
        // Stat
        $this->_adsEnt->ads_uses += 1;
        $this->_adsEnt->save();

        // Redirect
        return redirect($_rdr);
    }

/** Html */
    /**
     *
     */
    public function htmlAdsFrameAction(Request $request)
    {
        // Case: click ads?!
        $_rdr = trim($request->input('_rdr'));
        if ($_rdr) {
            return $this->_adsStat($_rdr);
        }
        //.end
        // Get, format ads's content
        $adsContent = $this->_adsEnt->getAdsContent();
        // +++
        $replaceData = [
            "query" => \http_build_query([ $this->_hash => '', '_rdr' => '__rdr__' ]),
            "attr_target" => '_parent',
        ];
        $p = '/<a[^>]*>/is';
        $adsContent = \preg_replace_callback($p = '/<a[^>]*>/is', function($m) use (&$replaceData) {
            $html = $m[0];
            // +++ href
            $html = \preg_replace_callback($p = '/href *= *[\'"]([^"]*)[\'"]/is', function($m) use (&$replaceData) {
                $attr = $m[0]; $href = $m[1] ?? null;
                if ($href) {
                    $attr = 'href="?' . \str_replace('__rdr__', \rawurlencode($href), $replaceData['query']) . '"';
                }
                return $attr;
            }, $html);
            // +++ target
            $attrFound = false;
            $html = \preg_replace_callback($p = '/target *= *[\'"]([^"]*)[\'"]/is', function($m) use (&$replaceData, &$attrFound) {
                $attrFound = true;
                return 'target="' . $replaceData['attr_target'] . '"';
            }, $html);
            if (!$attrFound) {
                $html = \str_replace('<a', '<a target="' . $replaceData['attr_target'] . '"', $html);
            }
            // Return;
            return $html;
        }, $adsContent);
        //.end

        // Render view
        return view('resources.html.ads_frame', [
            'host' => $request->getHost(),
            'hash' => $this->_hash,
            'data' => $this->_adsEnt,
            'adsContent' => $adsContent
        ]);
    }
/** .end#Html */

/** Javascript */
    /**
     * Widget ads
     */
    public function jsWidgetAdsAction(Request $request)
    {
        return static::_resJs('resources.js.ads-widget', [
            'host' => $request->getHost(),
            'hash' => $this->_hash,
            'data' => $this->_adsEnt
        ]);
    }
/** .end#Javascript */
}

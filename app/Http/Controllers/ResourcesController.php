<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Controllers\Controller;
// use App\Services\MobileDetect;

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
     * @var string
     */
    protected $_adsId = null;

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
        $params = \explode(';', \trim(\current($request->keys())));
        $this->_hash = $params[0] ?? "";
        $this->_adsId = $params[1] ?? "";
        $id = Models\Ads::decryptPriKey($this->_hash);
        $this->_adsEnt = Models\Ads::find4Resource($id);
        if (!$this->_adsEnt || ($this->_adsEnt && !$this->_adsEnt->isStatusActive())) {
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
            ->header('Content-Type', 'application/javascript')
        ;
    }

    /**
     * @TODO:...
     */
    protected function _adsStat(Request $request, $stat, array $opts = [])
    {
        // Stat
        $flag = 0;
        // +++ details
        $rptEnt = app()->make(Models\Rpt::class);
        $rptEnt->fill([
            'rpt_ads_id' => $this->_adsEnt->id(),
            'rpt_session' => $request->session()->getId(),
            'rpt_uri_fr' => $opts['rpt_uri_fr'] ?? '',
            'rpt_uri_to' => $opts['rpt_uri_to'] ?? '',
            'rpt_ua' => $request->userAgent(),
            'rpt_ips' => \implode(', ', $request->ips()),
            // 'rpt_extra' => \json_encode($_SERVER)
        ]);
        //.end
        // $mobileDetect = app()->make(MobileDetect::class);
        if ('uses' == $stat) {
            // details
            $rptEnt->fill([
                'rpt_type' => Models\Rpt::TYPE_USES
            ]);
            $this->_adsEnt->ads_uses += ($flag = 1);
        }
        if ('viewed' == $stat) {
            // details
            $rptEnt->fill([
                'rpt_type' => Models\Rpt::TYPE_VIEWED
            ]);
            //.end
            $this->_adsEnt->ads_viewed += ($flag = 1);
        }
        if ('clicked' == $stat) {
            // details
            $rptEnt->fill([
                'rpt_type' => Models\Rpt::TYPE_CLICKED
            ]);
            //.end
            $this->_adsEnt->ads_clicked += ($flag = 1);
        }
        if ($flag) {
            $rptEnt->save();
            $this->_adsEnt->save();
        }

        // Return
        return $this;
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
            // [stat]
            $this->_adsStat($request, 'clicked', [
                'rpt_uri_to' => $_rdr
            ]);
            //.end
            return redirect($_rdr);
        }
        //.end

        // Get, format ads's content
        $adsContent = $this->_adsEnt->getAdsContent();
        // +++
        $rptUriFr = trim($request->input('_fr'));
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
            // $attrFound = false;
            $html = \preg_replace_callback($p = '/target *= *[\'"]([^"]*)[\'"]/is', function($m) use (&$replaceData, &$attrFound) {
				$attr = $m[0]; $target = trim($m[1]) ?? null;
				if (\in_array($target, ['', '_self'])) {
					// $attrFound = true;
					$attr = ('target="' . $replaceData['attr_target'] . '"');
				}
                return $attr;
            }, $html);
            /* if (!$attrFound) {
                $html = \str_replace('<a', '<a target="' . $replaceData['attr_target'] . '"', $html);
            } */
            // Return;
            return $html;
        }, $adsContent);
        //.end

        // [stat]
        $this->_adsStat($request, 'viewed', [
            'rpt_uri_fr' => $rptUriFr
        ]);
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
        // [stat]
        $this->_adsStat($request, 'uses');
        //.end

        return static::_resJs('resources.js.ads-widget', [
            'host' => $request->getHost(),
            'hash' => $this->_hash,
            'adsId' => $this->_adsId,
            'adsEnt' => $this->_adsEnt
        ]);
    }
/** .end#Javascript */
}

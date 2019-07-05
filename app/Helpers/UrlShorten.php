<?php

namespace App\Helpers;

/**
 * @class UrlShorten
 */
class UrlShorten
{
    private $urlRepo = null;

    public function __construct()
    {
        $em = app('em');
        $this->urlRepo = $em->getRepository(\Models\Entities\Doctrine\Url::class);

        if(!$this->urlRepo){
            throw Exception('Validate url failed.');
        }
    }

    /**
     * @author T.Hùng 25/04/2017
     * @editor Mr.Phúc  13/09/2017
     * @param string $url_long đường dẫn dài
     * @param array $options chưa sử dụng
     * @return string url long
     */
     public function shorten($url_long, array $options = array())
     {
        $url_longArr = [
            $url_long,
            str_replace( getProtocol() . "://", "", $url_long ),
            str_replace( config('app.url'), "", $url_long ),
            str_replace( config('app.shortUrl'), "", $url_long ),
        ];

        $url = $this->urlRepo->fetchOptions(array(
            "url_long_in"      => $url_longArr,
            "resultMode"        => "OneOrNullEntities"
        ));

        if($url){
            return config('app.shortUrl') . "/" . $url->getUrlShort();
        }
        $urlNew = new \Models\Entities\Doctrine\Url();
        $urlNew->setUrlLong($url_long);

        while (true) {
            $url_short = str_random(5);
            $url = $this->urlRepo->fetchOptions(array(
                "url_short" => $url_short,
                "resultMode"        => "OneOrNullEntities"
            ));
            if(!$url){

                $urlNew->setUrlShort($url_short);
                break;
            }
        }
        $urlNew->setUrlCreateTime(time());
        if( $options["end_time"] ) {

            $urlNew->setUrlEndTime( $options["end_time"] );
        }
        $this->urlRepo->insert( $urlNew );

        return config('app.shortUrl') . "/" . $url_short;
    }
}

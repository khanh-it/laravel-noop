<?php

namespace App\Services;
use \Detection\MobileDetect as Mobile_Detect;

/**
 * Detect mobile
 *
 *
 * @access
 * @author KhanhDTP 2019-02-13
 * @copyright
 * @category
 * @package
 */
class MobileDetect extends Mobile_Detect
{
    /**
     * Check if the engine is webview
     * @see http://stackoverflow.com/questions/37591279/detect-if-user-is-using-webview-for-android-ios-or-a-regular-browser
     *
     * @return bool
     */
    public function isMobileWebview()
    {
        // Get UA string
        $userAgent = $this->getUserAgent();
        // Check if 'safari' is in UA string?
        $isUAContainSafari = !!preg_match('/safari/i', $userAgent);

        if ($this->isMobile() || $this->isTablet()) {
            if ($this->isiOS()) {
                if (!$isUAContainSafari) {
                    return true;
                }
            }
            if ($this->isAndroidOS()) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ('' != $_SERVER['HTTP_X_REQUESTED_WITH'])) {
                    return true;
                }
            }
        }
        return false;
    }
}

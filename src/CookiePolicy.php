<?php

namespace Fractas\CookiePolicy;

use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Extension;

class CookiePolicy extends Extension
{
    public static $include_cookie_policy_notification = true;
    public static $current_site_config = null;

    public function onBeforeInit()
    {
        $siteConfig = SiteConfig::current_site_config();
        self::set_current_site_config($siteConfig);
        self::set_cookie_policy_notification_enabled(self::$current_site_config->CookiePolicyIsActive);
    }

    public function onAfterInit()
    {
        if (self::cookie_policy_notification_enabled()) {
            $cookiepolicyjssnippet = new ArrayData(array(
                'CookiePolicyButtonTitle' => self::$current_site_config->CookiePolicyButtonTitle,
                'CookiePolicyDescription' => self::$current_site_config->CookiePolicyDescription,
                'CookiePolicyPosition' => self::$current_site_config->CookiePolicyPosition,
            ));

            Requirements::javascript('silverstripe/admin:thirdparty/jquery/jquery.js');
            Requirements::javascript('fractas/cookiepolicy:client/dist/javascript/jquery.cookie.policy.min.js');
            Requirements::customScript($cookiepolicyjssnippet->renderWith('CookiePolicyJSSnippet'));
        }
    }

    public static function set_current_site_config($input)
    {
        self::$current_site_config = $input;
    }

    public static function set_cookie_policy_notification_enabled($bool)
    {
        self::$include_cookie_policy_notification = (bool) $bool;
    }

    public static function cookie_policy_notification_enabled()
    {
        return self::$include_cookie_policy_notification;
    }
}

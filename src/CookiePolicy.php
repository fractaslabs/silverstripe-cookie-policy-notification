<?php

namespace Fractas\CookiePolicy;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class CookiePolicy extends Extension
{
    private static $include_cookie_policy_notification = true;
    private static $current_site_config = null;
    private static $load_jquery = false;
    private static $load_jquery_defer = false;
    private static $load_script_defer = true;

    public function onBeforeInit()
    {
        $siteConfig = SiteConfig::current_site_config();
        self::set_current_site_config($siteConfig);
        self::set_cookie_policy_notification_enabled(self::$current_site_config->CookiePolicyIsActive);
    }

    public function onAfterInit()
    {
        if (self::cookie_policy_notification_enabled()) {
            if (Config::inst()->get(static::class, 'load_jquery')) {
                Requirements::javascript('silverstripe/admin:thirdparty/jquery/jquery.js');
            }
            if (Config::inst()->get(static::class, 'load_jquery_defer')) {
                Requirements::javascript('silverstripe/admin:thirdparty/jquery/jquery.js', ['defer' => true]);
            }

            if (Config::inst()->get(static::class, 'load_script_defer')) {
                Requirements::javascript('fractas/cookiepolicy:client/dist/javascript/jquery.cookie.policy.min.js', ['defer' => true]);
            } else {
                Requirements::javascript('fractas/cookiepolicy:client/dist/javascript/jquery.cookie.policy.min.js');
            }
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

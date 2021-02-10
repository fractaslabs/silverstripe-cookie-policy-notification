<?php

namespace Fractas\CookiePolicy;

use SilverStripe\Core\Config\Config;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;

class CookiePolicyController extends Controller
{
    private static $current_site_config = null;

    private static $allowed_actions = [
        'index',
    ];

    private static $url_handlers = [
        'fetchcookiepolicy' => 'index',
    ];

    public function index(HTTPRequest $request)
    {
        $this->getResponse()->setBody(json_encode([
            'CookiePolicyButtonTitle' => $this->owner->getCookiePolicyButtonTitle(),
            'CookiePolicyDescription' => $this->owner->getCookiePolicyDescription(),
            'CookiePolicyPosition' => $this->owner->getCookiePolicyPosition(),
            'Reload' => $this->owner->getreload(),
        ]));

        $this->getResponse()->addHeader("Content-type", "application/json");

        return $this->getResponse();
    }

    public function doInit()
    {
        $siteConfig = SiteConfig::current_site_config();
        self::set_current_site_config($siteConfig);
    }

    public function getCookiePolicyButtonTitle()
    {
        return self::$current_site_config->CookiePolicyButtonTitle;
    }

    public function getCookiePolicyDescription()
    {
        return self::$current_site_config->obj('CookiePolicyDescription')->RAW();
    }

    public function getCookiePolicyPosition()
    {
        return self::$current_site_config->CookiePolicyPosition;
    }

    public function getReload()
    {
        return Config::inst()->get(CookiePolicy::class, 'reload_on_accept');
    }

    public static function set_current_site_config($input)
    {
        self::$current_site_config = $input;
    }
}

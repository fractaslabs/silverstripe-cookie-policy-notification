<?php

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
                'CookiePolicyPosition' => self::$current_site_config->CookiePolicyPosition
            ));

            Requirements::javascript(THIRDPARTY_DIR.'/jquery/jquery.js');
            Requirements::javascript('cookiepolicy/javascript/jquery.cookie.policy.min.js');
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

class CookiePolicy_Settings extends DataExtension
{

    private static $db = array(
        'CookiePolicyButtonTitle' => 'Varchar',
        'CookiePolicyDescription' => 'HTMLText',
        'CookiePolicyPosition' => "Enum('top, bottom', 'bottom')",
        'CookiePolicyIsActive' => 'Boolean'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab("Root", new Tab('CookiePolicy'));
        $fields->addFieldToTab('Root.CookiePolicy', CheckboxField::create("CookiePolicyIsActive")->setTitle(_t('CookiePolicy.ISACTIVE', "Is Active")));
        $fields->addFieldToTab('Root.CookiePolicy', TextField::create("CookiePolicyButtonTitle")->setTitle(_t('CookiePolicy.BUTTONTITLE', "Button Title")));
        $fields->addFieldToTab('Root.CookiePolicy', HtmlEditorField::create("CookiePolicyDescription")->setTitle(_t('CookiePolicy.DESCRIPTION', "Description")));
        $fields->addFieldToTab('Root.CookiePolicy', DropdownField::create("CookiePolicyPosition")->setSource(singleton('SiteConfig')->dbObject('CookiePolicyPosition')->enumValues())->setTitle(_t('CookiePolicy.POSITION', "Position")));
    }
}

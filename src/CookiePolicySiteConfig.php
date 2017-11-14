<?php

namespace Fractas\CookiePolicy;

use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class CookiePolicySiteConfig extends DataExtension
{
    private static $db = array(
        'CookiePolicyButtonTitle' => 'Varchar',
        'CookiePolicyDescription' => 'HTMLText',
        'CookiePolicyPosition' => "Enum('top, bottom', 'bottom')",
        'CookiePolicyIsActive' => 'Boolean',
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.CookiePolicy',
            CheckboxField::create('CookiePolicyIsActive')
                ->setTitle(_t('CookiePolicy.ISACTIVE', 'Cookie Policy Notification Is Active?'))
        );
        $fields->addFieldToTab(
            'Root.CookiePolicy',
            TextField::create('CookiePolicyButtonTitle')
                ->setTitle(_t('CookiePolicy.BUTTONTITLE', 'Notification Button Title'))
        );
        $fields->addFieldToTab(
            'Root.CookiePolicy',
            HtmlEditorField::create('CookiePolicyDescription')
                ->setTitle(_t('CookiePolicy.DESCRIPTION', 'Notification Description'))
        );
        $fields->addFieldToTab(
            'Root.CookiePolicy',
            DropdownField::create('CookiePolicyPosition')
                ->setSource(singleton(SiteConfig::class)->dbObject('CookiePolicyPosition')->enumValues())
                ->setTitle(_t('CookiePolicy.POSITION', 'Notification Position On Page'))
        );
    }
}

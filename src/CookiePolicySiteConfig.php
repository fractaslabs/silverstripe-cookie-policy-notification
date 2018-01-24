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

    /**
     * Exclude fields from translating via Fluent config.
     */
    private static $field_exclude = [
        'CookiePolicyPosition',
        'CookiePolicyIsActive'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.CookiePolicy', [
                CheckboxField::create('CookiePolicyIsActive')
                    ->setTitle(_t(__CLASS__.'IsActive', 'Cookie Policy Notification Is Active?')),
                TextField::create('CookiePolicyButtonTitle')
                    ->setTitle(_t(__CLASS__.'Buttontitle', 'Notification Button Title')),
                HtmlEditorField::create('CookiePolicyDescription')
                    ->setTitle(_t(__CLASS__.'Description', 'Notification Description')),
                DropdownField::create('CookiePolicyPosition')
                    ->setSource(singleton(SiteConfig::class)->dbObject('CookiePolicyPosition')->enumValues())
                    ->setTitle(_t(__CLASS__.'Position', 'Notification Position On Page'))
            ]
        );
    }
}

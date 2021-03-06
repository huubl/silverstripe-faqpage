<?php

namespace Hestec\FaqPage;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Security\Permission;

class FaqCategory extends DataObject {

    private static $table_name = 'HestecFaqCategory';

    private static $singular_name = 'Category';
    private static $plural_name = 'Categories';

    private static $db = [
        'Title' => 'Varchar(255)',
        'Sort' => 'Int'
    ];

    private static $has_one = [
        //'Page' => FaqPage::class
        'Page' => SiteTree::class
    ];

    private static $has_many = [
        'Questions' => FaqQuestion::class
    ];

    private static $summary_fields = [
        'Title',
        'Questions.Count',
        'Created'
    ];

    private static $default_sort = 'Sort';

    public function summaryFields()
    {
        $fields = parent::summaryFields();
        $fields['Questions.Count'] = _t(self::class . '.NUMBER_OF_QUESTIONS', 'Number of questions');
        return $fields;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('PageID');
        $fields->removeByName('Questions');
        $fields->removeByName('Sort');

        if ($this->ID){

            $gridConfig = GridFieldConfig_RecordEditor::create();
            $gridConfig->addComponent(new GridFieldOrderableRows());
            $gridConfig->addComponent(new GridFieldDeleteAction());
            $fields->addFieldToTab('Root.Main', new GridField('Questions', _t(self::class . '.QUESTIONS', 'Questions'), $this->Questions(), $gridConfig));

        }else{

            $InfoField = LiteralField::create('InfoField', _t("FaqCategory.SAVE_FIRST_TIME", "You can add questions after you have saved the categorie for the first time."));
            $fields->addFieldToTab('Root.Main', $InfoField);

        }


        return $fields;
    }

    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

}

<?php
/**
 * @version 0.1
 * @package Newspaper Page Module
 */

class NewspaperArticle extends Page {

    private static $db = array(
        'Headline' => 'Varchar(200)',
        'Date' => 'Date',
        'Excerpt' => 'HTMLText'
    );

    private static $has_one = array(
        'ArticleImage' => 'Image'
    );

    private static $plural_name = 'Newspaper articles';
    private static $singular_name = 'Newspaper article';
    private static $icon = 'newspaperModule/images/treeicons/newspaperArticle-icon.gif';

    private static $defaults = array(
        "ShowInMenus" => 0
    );

    private static $can_be_root = false;

    /**
     * Set additional fields for cms.
     */
    public function getCMSFields() {
        $oDateField = new DateField('Date', _t('NewspaperArticle.DATE', 'Date'));
        $oDateField->setConfig('datavalueformat', 'yyyy-MM-dd');
        $oDateField->setConfig('showcalendar', true);

        $oHeadlineField = new TextField('Headline', _t('NewspaperArticle.HEADLINE', 'Headline'));
        $oHeadlineField->setMaxLength(200);

        $oArticleImage = new UploadField('ArticleImage', _t('NewspaperArticle.ARTICLEIMAGE', 'Article Image'));
        $oArticleImage->setFolderName('NewspaperModule/'.$this->Parent()->URLSegment);
        $oArticleImage->setAllowedMaxFileNumber(1);
        $oArticleImage->setAllowedFileCategories('image');
        
        $oExcerptField = new HtmlEditorField("Excerpt", _t('NewspaperArticle.EXCERPT', "Excerpt"));

        $fields = parent::getCMSFields();
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                $oArticleImage,
                $oDateField,
                $oHeadlineField,
                $oExcerptField
            ),
            'Content'
        );

        return $fields;
    }

    /**
     * Remove ShowInMenus field from settings.
     */
    public function getSettingsFields() {
        $fields = parent::getSettingsFields();
        $fields->removeFieldFromTab('Root', 'ShowInMenus');
        $fields->removeFieldFromTab('Root', 'ParentType');
        $fields->removeFieldFromTab('Root', 'ParentID');

        return $fields;
    }

    public function populateDefaults() {
        $this->Date = date('yyyy-MM-dd');
        $this->ShowInMenus = 0;
        parent::populateDefaults();
    }
}

class NewspaperArticle_Controller extends Page_Controller {

    public function init() {
        parent::init();
    }
}

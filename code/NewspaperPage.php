<?php
/**
 * @version 0.1
 * @package Newspaper Page Module
 */

class NewspaperPage extends Page {

    private static $db = array(
        'ImageFolderURLSegment' => 'varchar(100)'
    );

    private static $has_one = array(
        'ImageFolder' => 'Folder'
    );

    private static $has_many = array(
        'NewspaperArticles' => 'NewspaperArticle'
    );

    private static $summary_fields = array(
        'Title' => 'Varchar'
    );

    private static $singular_name = 'NewspaperPage';
    private static $plural_name = 'NewspaperPages';
    private static $description = 'Overview for newspaper articles';
    private static $icon = 'newspaperModule/images/treeicons/newspaperPage-icon.gif';
    private static $default_child = "NewspaperArticle";

    /**
     * Set the fields for cms.
     *
     * @return FieldList fields to show in cms
     */
    public function getCMSFields() {
        $fields = parent::getCMSFields();

        return $fields;
    }

    public function onBeforeWrite() {
        $this->handleImageDirectory();
        parent::onBeforeWrite();
    }
    
    public function onAfterWrite() {
        $this->handleImageThumbs();
        parent::onAfterWrite();
    }

    public function onBeforeDelete() {
        $this->deleteImageDirectory();
        parent::onBeforeDelete();
    }

    /**
     * Handles the link to image directory of a newspaper page. If a new newspaper page has been created and there 
     * is'nt already an image directory set, create it and set the has_one link to it. If the URLSegment has been
     * changed, rename the image folder matching to URLSegment.
     */
    public function handleImageDirectory() {
        if(0 == $this->ImageFolderID) {
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            // If there is no ImageFolderURLSegment set, set it to default folder '/assets/NewspaperModule/Default'
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            $oDefaultFolder = Folder::find_or_make('NewspaperModule/Default');
            $this->ImageFolderID = $oDefaultFolder->ID;
        } else {
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            // Link to an image folder already exists 
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            if($this->isChanged('URLSegment', 2)) {
                // If URLSegment has been changed, rename ImageFolderURLSegment
                $oImageFolder = Folder::get_by_id('Folder', $this->ImageFolderID);

                if(!$oImageFolder) {
                    // If the directory does'nt exist (e.g. it was deleted manually), create the directory
                    // with the new name.
                    Folder::find_or_make('NewspaperModule/'.$this->URLSegment);
                } else {
                    // If the directory exists, rename it matching the URLSegment
                    $oImageFolder->setName($this->URLSegment);
                    $oImageFolder->write();
                }
		    }
		}
    }

    /**
     * Deletes the image folder of a newspaper page, if the page is already deleted from live or from draft.
     *  
     */
    public function deleteImageDirectory() {
        if ($this->getIsDeletedFromStage() xor $this->getIsAddedToStage()) {
            $oImageFolder = Folder::get_by_id('Folder', $this->ImageFolderID);

            if ($oImageFolder) {
                $oImageFolder->delete();
            }
        }
    }

    /**
     * If the setting field "autodelete article image" is activated, delete the image of the article while
     * deleting article itself
     *
     * @todo Implement this method
     */
    public function handleImageThumbs() {
        return true;
    }

    /**
     * Get only children with page type "articlePage"
     *
     * @return SS_List
     */
    public function NewspaperArticleList() {
        $oChildren = NewspaperArticle::get()
            ->filter('ParentID', (int)$this->ID)
            ->exclude('ID', (int)$this->ID)
            ->setDataQueryParam(array(
                'Versioned.stage' => 'Live'
		    ));
			
        return $oChildren;
    }

    /**
     * Return folder name for image folder of this NewspaperPage to create subfolder of "assets/NewspaperModule/"
     *
     * @return string
     */
    public function getFolderName() {
        return $this->URLSegment;
    }

    /**
     * Returns the main article image width
     *
     * @return int
     */
    public function getMainImageWidth() {
        return (int)$this->config()->get('MainImageWidth');
    }

    /**
     * Returns the main article image height
     *
     * @return int
     */
    public function getMainImageHeight() {
        return (int)$this->config()->get('MainImageHeight');
    }

    /**
     * Returns the article image width
     *
     * @return int
     */
    public function getImageWidth() {
        return (int)$this->config()->get('ImageWidth');
    }

    /**
     * Returns the article image height
     *
     * @return int
     */
    public function getImageHeight() {
        return (int)$this->config()->get('ImageHeight');
    }
}


class NewspaperPage_Controller extends Page_Controller {

    public function init() {
        if(Director::fileExists(project() . "/css/newspaperPage.css")) {
            Requirements::css(project() . "/css/newspaperPage.css");
        } else {
            Requirements::css("newspaperModule/css/newspaperPage.css");
        }

        parent::init();
    }
}

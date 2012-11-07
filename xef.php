<?php
/**
 *  @package Expert Extension Framework (XEF)
 *  @copyright Copyright (c)2010-2012 ThemeXpert.com
 *  @license GNU General Public License version 3, or later
 **/

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Expert Extension Framework (XEF) helper class
 *
 * Expert Extension Framework (XEF) is a set of classes which extends Joomla! 2.5 and later's
 * MVC framework with features making maintaining all ThemeXpert's extensions much easier.
 *
 * Initially designed for module development and more coming...
 */

class XEFHelper
{
    public $params;        // Hold the extension params. we'll not allow to access it from outside.
    public $module;        // Module object hold the module information

    /*
     * Constructor
     * @params object $params get the module params object
     *
     * @return NULL
     *
     **/
    public function __construct( $module, $params )
    {
        $this->module       = $module;
        $this->params       = $params;

        if( !defined('XEF_JVERSION') )
        {
            if ( version_compare(JVERSION, '2.5', 'ge') && version_compare(JVERSION, '3.0', 'lt') )
            {
                define('XEF_JVERSION', '25');
            }else{
                define('XEF_JVERSION', '30');
            }
        }


    }

    /*
     * Get the value of given param or set to default
     *
     * @params string $param    name of the field
     *
     * @default string $default set default value if no value found on param
     *
     * @return string/int $value    Value return for given field
     *
     **/
    public function get( $param , $default=NULL )
    {
        $value = ( $this->params->get($param) != NULL ) ? $this->params->get($param) : $default;

        return $value;
    }

    /*
     * Set the value to given param
     *
     * @params string $field    name of the field
     *
     * @value string/int $value  set value to the field
     *
     **/
    public function set( $field, $value )
    {
        $this->params->set( $field, $value );
    }

    /*
     * Prepare items before going to view.
     *
     * @params object $items Items object
     *
     * @return Object $items modified items object
     *
     **/
    public function prepareItems($items)
    {
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));

        $source = $this->get('content_source');

        foreach ($items as $item)
        {
            if( $source == 'joomla' )
            {
                $item->slug = $item->id.':'.$item->alias;
                $item->catslug = $item->catid.':'.$item->category_alias;

                if ($access || in_array($item->access, $authorised))
                {
                        // We know that user has the privilege to view the article
                        $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
                }
                else {
                        $item->link = JRoute::_('index.php?option=com_users&view=login');
                }
                // category name & link
                $item->catname = $item->category_title;
                $item->catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));

            }elseif($source == 'k2')
            {
                $item->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->categoryalias))));

                // category name & link
                $item->catname = $item->categoryname;
                $item->catlink = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->catid.':'.urlencode($item->categoryalias))));

            }

            //Clean title
            $item->title = JFilterOutput::ampReplace($item->title);

            $dimensions = array(
                'width'  => $this->get('image_width',400),
                'height' => $this->get('image_height',300)
            );

            //Take advantage from joomla default Intro image system
            if( isset($item->images) )
            {
                $images = json_decode($item->images);
            }

            if( isset($images->image_intro) and !empty($images->image_intro) )
            {
                $item->image = $images->image_intro;

            }else{
                //get image from article intro text
                $item->image = XEFUtility::getImage($item->introtext);
            }

            if( $this->get('navigation') == 'thumb' OR
                $this->get('thumb') )
            {

                $thumb_dimensions = array(
                    'width'  => $this->get('thumb_width',100),
                    'height' => $this->get('thumb_height',100)
                );
                $item->thumb = XEFUtility::getResizedImage($item->image, $thumb_dimensions, $this->module, '_thumb');
            }

            $item->image = XEFUtility::getResizedImage($item->image, $dimensions, $this->module);

            //Intro text
            $limit_type = $this->get('intro_limit_type');

            if( $limit_type == 'words' )
            {
                $item->introtext = XEFUtility::wordLimit($item->introtext, $this->get('intro_limit',100) );

            }elseif($limit_type == 'chars')
            {
                $item->introtext = XEFUtility::characterLimit($item->introtext, $this->get('intro_limit',100) );
            }

        }

        return $items;
    }

}
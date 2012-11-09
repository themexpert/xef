<?php
/**
 *  @package Expert Extension Framework (XEF)
 *  @copyright Copyright (c)2010-2012 ThemeXpert.com
 *  @license GNU General Public License version 3, or later
 **/

// Protect from unauthorized access
defined('_JEXEC') or die();

// Require XEF helper class
require_once 'xef.php';

// Require the utility class
require_once 'utility.php';

class XEFJoomla extends XEFHelper
{

    public function getItems()
    {

        require_once JPATH_SITE.'/components/com_content/helpers/route.php';
        jimport('joomla.application.component.model');

        $app = JFactory::getApplication('site', array(), 'J');

        // Get the dbo
        $db = JFactory::getDbo();

        // Get an instance of the generic articles model
        if(XEF_JVERSION == '25')
        {
            JModel::addIncludePath(JPATH_SITE.'/components/com_content/models');
            $model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

        }else{

            JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models');
            $model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
        }

        // Set application parameters in model
        $appParams = $app->getParams();
        $model->setState('params', $appParams);

        // Set the filters based on the module params
        $model->setState('list.start', 0);
        $model->setState('list.limit', $this->get( 'count', 4 ));
        $model->setState('filter.published', 1);

        // Access filter
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
        $model->setState('filter.access', $access);

        // Category filter
        switch( $this->get('jomcatfilter') )
        {
            case 0: // All
                $catid = '';
                break;
            case 1: // From specific category
                $catid = $this->get('jom_catid',array());
                break;
        }

        $model->setState( 'filter.category_id', $catid );

        // User filter
        $userId = JFactory::getUser()->get('id');
        switch ($this->get('jom_user_id'))
        {
            case 'by_me':
                $model->setState('filter.author_id', (int) $userId);
                break;
            case 'not_me':
                $model->setState('filter.author_id', $userId);
                $model->setState('filter.author_id.include', false);
                break;

            case '0':
                break;

            default:
                $model->setState('filter.author_id', (int) $this->get('jom_user_id'));
                break;
        }

        // Filter by language
        $model->setState('filter.language',$app->getLanguageFilter());

        //  Featured switch
        switch ($this->get('jom_show_featured'))
        {
            case '1':
               $model->setState('filter.featured', 'only');
               break;
            case '0':
               $model->setState('filter.featured', 'hide');
               break;
            default:
               $model->setState('filter.featured', 'show');
               break;
        }

       // Set ordering
       $order_map = array(
               'm_dsc' => 'a.modified DESC, a.created',
               'mc_dsc' => 'CASE WHEN (a.modified = '.$db->quote($db->getNullDate()).') THEN a.created ELSE a.modified END',
               'c_dsc' => 'a.created',
               'p_dsc' => 'a.publish_up',
       );

        $ordering = JArrayHelper::getValue($order_map, $this->get('jom_ordering'), 'a.publish_up');
        $dir = 'DESC';

        $model->setState('list.ordering', $ordering);
        $model->setState('list.direction', $dir);
        $items = $model->getItems();

        // Prepare the items with options
        $items = $this->prepareItems($items);

        //XEFUtility::debug($catid);

       return $items;
    }
}
<?php

/**
 * @package Expose
 * @subpackage Xpert Contents
 * @version 2.5
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldUtility extends JFormField
{

    protected  $type = 'Utility';

    protected function getInput()
    {

        $doc        = JFactory::getDocument();

        if (JVERSION < 4) {

            $path_css   = JURI::root(true) . '/libraries/xef/assets/css';
            $path_js    = JURI::root(true) . '/libraries/xef/assets/js';
            $doc->addStyleSheet($path_css . '/bootstrap.css');

            // echo ($path_css .'/jquery-ui-1.8.16.custom.css');

            //load all CSS first
            $doc->addStyleSheet($path_css . '/jquery-ui-1.8.16.custom.css');



            //J2.5 and 3.0 js list to load
            $j25 = array('jquery-1.8.2.min.js', 'jquery-ui-1.8.16.custom.min.js', 'bootstrap.js', 'bootstrap-modal.js', 'chosen.jquery.min.js');
            $j30 = array('jquery-ui-1.8.16.custom.min.js', 'bootstrap-modal.js');

            if (version_compare(JVERSION, '2.5', 'ge') && version_compare(JVERSION, '3.0', 'lt')) {
                foreach ($j25 as $js) {
                    $doc->addScript($path_js . '/' . $js);
                }
                $doc->addStyleSheet($path_css . '/chosen.css');
            } else {
                foreach ($j30 as $js) {
                    $doc->addScript($path_js . '/' . $js);
                }
            }
        } else {
            // $path_css   = JURI::root(true) . '/media/xef/css_4';
            $path_js    = JURI::root(true) . '/media/xef/js_4';

            // $doc->addStyleSheet($path_css .'/bootstrap.css');

            // $js_files = array('bootstrap.min.js.map', 'bootstrap.min.js', 'bootstrap.js.map', 'bootstrap.js', 'bootstrap.bundle.min.js.map', 'bootstrap.bundle.min.js', 'bootstrap.bundle.js.map', 'bootstrap.bundle.js');

            // foreach($js_files as $js){
            //     $doc->addScript($path_js . '/'. $js );
            // }

            $doc->addScript($path_js . '/bootstrap.min.js');
        }

        // echo $path_js;
        $path_js    = JURI::root(true) . '/media/xef/js';

        $doc->addScript($path_js . '/admin.js');
    }

    protected function getLabel()
    {
        return '';
    }
}

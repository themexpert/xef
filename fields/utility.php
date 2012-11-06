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
defined( '_JEXEC' ) or die('Restricted access');


jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldUtility extends JFormField{

    protected  $type = 'Utility';

    protected function getInput(){

        $doc        = JFactory::getDocument();
        $path_css   = JURI::root(true) . '/libraries/xef/assets/css';
        $path_js    = JURI::root(true) . '/libraries/xef/assets/js';

        //load all CSS first
        $doc->addStyleSheet($path_css .'/jquery-ui-1.8.16.custom.css');
        $doc->addStyleSheet($path_css .'/bootstrap.css');

        //load jquery and plugins
        $doc->addScript( $path_js . '/jquery-1.8.2.min.js' );
        $doc->addScript( $path_js . '/jquery-ui-1.8.16.custom.min.js' );
        $doc->addScript( $path_js . '/bootstrap.js');
        $doc->addScript( $path_js . '/bootstrap-modal.js'); // Modified BS modal plugin to avoid conflict with Joomla
        $doc->addScript( $path_js . '/chosen.jquery.min.js');

        //load admin script
        $doc->addScript( $path_js . '/admin.js');


    }

    protected function getLabel(){
        return '';
    }
}



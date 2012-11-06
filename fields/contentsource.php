<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// no direct access
defined( '_JEXEC' ) or die('Restricted access');

/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldContentSource extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'ContentSource';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
        //TODO: cURL Check and add warning for flickr and youtubre
        $html = '';

        //check component and add warning or success info on module
        $k2 = JPATH_SITE.DS."components".DS."com_k2".DS."k2.php";
        $easyblog = JPATH_SITE.DS."components".DS."com_easyblog".DS."easyblog.php";
        $provider = $this->element['provider'] ? $this->element['provider'] : 'joomla, k2';
        $providers = explode(',', $provider);
        $status = array();
        $list = '';

        $k2status = $ebstatus = '';

        // Joomla is available for all :D
        $status['joomla'] = array('status'=>'available', 'warning'=>'');

        if (!file_exists($k2))
        {
            $k2status = 'notavailable';
            $k2Warning = 'data-original-title="Not Available!" data-content="K2 Not Found. In order to use the K2 Content type, you will need to download and install it from http://www.getk2.org."';
        }
        else
        {
            $k2status = 'available';
            $k2Warning = '';
        }
        $status['k2'] = array('status'=>$k2status, 'warning'=>$k2Warning);

        if(!file_exists($easyblog))
        {
            $ebstatus = 'notavailable';
            $ebWarning = "data-original-title=\"Not Available!\" data-content=\"EasyBlog Not Found. In order to use the EasyBlog Content type, you will need to download and install it from http://www.stackideas.com.\"";
        }
        else
        {
            $ebstatus = 'available';
            $ebWarning = '';
        }
        $status['easyblog'] = array('status'=>$ebstatus, 'warning'=>$ebWarning);

        $ytWarning = "data-original-title='Not Available!' data-content='Youtube is not available right now. Coming soon.'";

        $status['youtube'] = array('status'=>'notavailable', 'warning'=>$ytWarning);

        $html .= '<a class="btn cs-btn" data-toggle="bsmodal" href="#content-source" ><span>'.JText::_('SELECT_CONTENT_SOURCE_BTN').'</span></a>';

        foreach($providers as $ext)
        {
            $st = $status[$ext]['status'];
            $warn = $status[$ext]['warning'];
            $list .= "<li>
                        <a class=\"$ext $st\" href=\"#\" rel=\"popover\" $warn>
                            <span>$ext</span>
                        </a>
                      </li>";
        }

        $html .=
            '<div class="bsmodal hide fade" id="content-source">
                  <div class="bsmodal-header">
                    <button class="close" data-dismiss="bsmodal">×</button>
                    <h3>'.JText::_('SELECT_CONTENT_SOURCE').'</h3>
                  </div>
                  <div class="bsmodal-body">
                    <ul class="cs-list">
                        '. $list .'
                    </ul>
                  </div>
            </div>';

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		$html .= '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . '/>';

        return $html;
	}
}

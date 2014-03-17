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

class JFormFieldSpacer extends JFormField{

    protected $type = 'Spacer';

    protected function getInput()
    {
        $html   = array();
        $class  = (string) $this->element['class'];
        $label  = '';
        $kind   = (string) $this->element['kind'];
        $start  =  $this->element['start'];
        $end    = (int) $this->element['end'];

        // Get the label text from the XML element, defaulting to the element name.
        $text = $this->element['label'] ? (string) $this->element['label'] : '';

        $output = '';

        if( $kind == 'wrap' AND $start )
        {
            $output = '</li><div id="'. $class .'" class="cs-options clearfix">';
            $label .= '<div class="spacer'.(($text != '') ? ' hasText hasTip' : '').'" title="'. JText::_($this->description) .'"><span>' . JText::_($text) . '</span></div>';
            return $output . $label ;

        }elseif( $end )
        {
            return $output = '</div><li>';
        }else
        {
            // Add the label text and closing tag.
            if($text != NULL){
                $label .= '<div class="spacer'.(($text != '') ? ' hasText hasTip' : '').'" title="'. JText::_($this->description) .'"><span>' . JText::_($text) . '</span></div>';
            }

            $html[] = $label;

            return implode('', $html);    
        }

        
    }

    protected function getLabel(){
       return ; 
    }
}


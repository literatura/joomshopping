<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class JshoppingViewRefreshprices extends JViewLegacy
{
    function display($tpl = null){
        JToolBarHelper::title( _JSHOP_REFRESHPRICES, 'generic.png' ); 
        parent::display($tpl);
	}
}
?>
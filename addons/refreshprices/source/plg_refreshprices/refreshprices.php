<?php
defined('_JEXEC') or die('Restricted access');
?>
<?php
class plgJshoppingMenuRefreshPrices extends JPlugin
{
	function plgJshoppingOrderUpdatePrefreshPrices(&$subject, $config){
        die();
        JSFactory::loadExtLanguageFile('refreshprices');
		parent::__construct($subject, $config);
		if (!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('jshoppingmenu', 'refreshprices');
			$this->params = new JParameter( $plugin->params );
		}
    }
    
    function onBeforeAdminOptionPanelMenuDisplay(&$menu)
    {
        JSFactory::loadExtLanguageFile('refreshprices');
   	    $menu['refreshprices'] = array(_JSHOP_REFRESHPRICES, 'index.php?option=com_jshopping&controller=refreshprices', 'excel.png', 1);   

    }
    function onBeforeAdminOptionPanelIcoDisplay(&$menu)
    {
        JSFactory::loadExtLanguageFile('refreshprices');
   		$menu['refreshprices'] = array(_JSHOP_REFRESHPRICES, 'index.php?option=com_jshopping&controller=refreshprices', 'excel.png', 1);  	
    }
}
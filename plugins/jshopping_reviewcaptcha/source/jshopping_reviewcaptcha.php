<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');

class plgJshoppingproductsJshopping_reviewcaptcha extends JPlugin{

    function __construct(&$subject, $config){
        parent::__construct($subject, $config);
    }

	function onBeforeDisplayProductView(&$view){
		$plugin = JComponentHelper::getParams('com_users')->get('captcha', JFactory::getConfig()->get('captcha'));
		if ($plugin === 0 || $plugin === '0' || $plugin === '' || $plugin === null) return;
		
		$language = JFactory::getLanguage();
		$language->load('com_users');
		
		$captcha = new JCaptcha($plugin, array());
		$view->_tmp_product_review_before_submit = '<div class = "jshop_register"><table><tr><td class="name">'.JText::_('COM_USERS_CAPTCHA_LABEL').' <span>*</span></td><td>'.$captcha->display('jshopping_captcha', 'jshopping_captcha', 'jshopping_captcha').'</td></tr></table></div>';
	}

	function onBeforeSaveReview(&$review){
		$plugin = JComponentHelper::getParams('com_users')->get('captcha', JFactory::getConfig()->get('captcha'));
		if ($plugin === 0 || $plugin === '0' || $plugin === '' || $plugin === null) return;
		
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
		$language = JFactory::getLanguage();
		$language->load('plg_captcha_'.$plugin);
		
		$captcha = new JCaptcha($plugin, array());
		if (!$captcha->checkAnswer($plugin)) {
			$error = $captcha->getError();
			if ($error instanceof Exception) {
				JError::raiseWarning('', $error);
			} else {
				JError::raiseWarning('', new JException($error));
			}
			$app->redirect(SEFLink("index.php?option=com_jshopping&controller=user&task=register",1,1, $jshopConfig->use_ssl));
		}
	}
}
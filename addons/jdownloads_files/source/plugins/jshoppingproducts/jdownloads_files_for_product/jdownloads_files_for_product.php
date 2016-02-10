<?php
defined('_JEXEC') or die('Restricted access');

class plgJshoppingProductsjdownloads_files_for_product extends JPlugin
{
	var $sep1 = "{jdownloads_cat_id}";
	var $sep2 = "{/jdownloads_cat_id}";
	
	function __construct(&$subject, $config){
		parent::__construct($subject, $config);
    }
    
	function onBeforeDisplayProduct(&$product, &$view, &$product_images, &$product_videos, &$product_demofiles) {
		$lang = JSFactory::getLang();

		$start = mb_strpos($product->description, $this->sep1);
		$end = mb_strpos($product->description, $this->sep2);

		$product->jdownloads_cat_id = 0;

		if($end!=false){				
			$product->jdownloads_cat_id = mb_substr($product->description, ($start+mb_strlen($this->sep1)), ($end-$start-mb_strlen($this->sep1)));
			$product->description = mb_substr($product->description, ($end+mb_strlen($this->sep2)));

			if (!isset($view->_tmp_product_html_jdownloads)) $view->_tmp_product_html_jdownloads = '';
			if($product->jdownloads_cat_id != 0){
				jimport( 'joomla.application.module.helper' );
				$module = JModuleHelper::getModule( 'mod_jdownloads_category' );
				$params = new JRegistry();
				$params->loadString($module->params);				
				$params->set('cat_id', $product->jdownloads_cat_id);
				$module->params = $params->toString();
				$view->_tmp_product_html_jdownloads = JModuleHelper::renderModule( $module );
			}
		}
	}
	
}
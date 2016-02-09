<?php
defined('_JEXEC') or die('Restricted access');

class plgJshoppingProductsjdownloads_files_for_product extends JPlugin
{
	var $separator = "{jdownloads_cat_id}";
	
	function __construct(&$subject, $config){
		parent::__construct($subject, $config);
    }
    
	function onBeforeDisplayProduct(&$product, &$view, &$product_images, &$product_videos, &$product_demofiles) {
		$lang = JSFactory::getLang();
        $description = $lang->get('description');
		$second_description = 'second_'.$description;
		$tmp = explode($this->separator, $product->$description);
		if (isset($tmp[1])) {
			$product->description = $tmp[0];
			$product->second_description = $tmp[1];
			if (!isset($view->_tmp_product_html_after_buttons)) $view->_tmp_product_html_after_buttons = '';
			$view->_tmp_product_html_after_buttons .= '<div class="second_description">'.$product->second_description.'</div>';
		}
	}
	
}
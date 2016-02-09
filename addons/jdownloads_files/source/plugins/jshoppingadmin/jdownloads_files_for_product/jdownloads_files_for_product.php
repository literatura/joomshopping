<?php
defined('_JEXEC') or die('Restricted access');

class plgJshoppingAdminjdownloads_files_for_product extends JPlugin
{
	var $separator = "{jdownloads_cat_id}";
	
	function __construct(&$subject, $config){
		parent::__construct($subject, $config);
	}

	function onBeforeDisplaySaveProduct(&$post, &$product) {
		$_lang = JModelLegacy::getInstance('Languages','JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
            $post['description_'.$lang->language] .= $this->separator . JRequest::getVar('jdownloads_cat_id_'.$lang->id,'','post',"string", 2);
        }
	}
	
	function onBeforeDisplayEditProduct(&$product, &$related_products, &$lists, &$listfreeattributes, &$tax_value) {
		$_lang = JModelLegacy::getInstance('Languages','JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
			$tmp = explode($this->separator, $product->{'description_'.$lang->language});
			if (isset($tmp[1])) {
				$product->{'description_'.$lang->language} = $tmp[0];
				$product->{'jdownloads_cat_id_'.$lang->language} = $tmp[1];
			} else {
				$product->{'jdownloads_cat_id_'.$lang->language} = '';
			}
        }
	}
	
	function onBeforeDisplayEditProductView(&$view) {
		$_lang = JModelLegacy::getInstance('Languages','JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
			if (isset($view->product->{'jdownloads_cat_id_'.$lang->language})) {
				if (!isset($view->{'plugin_template_description_'.$lang->language})) $view->{'plugin_template_description_'.$lang->language} = '';
				$second_description = "jdownloads_cat_id".$lang->language;
				$view->{'plugin_template_description_'.$lang->language} .= '<tr><td class="key">ID категории jDownloads</td><td><input type="text" name="jdownloads_cat_id_'.$lang->id.'" /></td></tr>';
			}
		}
	}

}
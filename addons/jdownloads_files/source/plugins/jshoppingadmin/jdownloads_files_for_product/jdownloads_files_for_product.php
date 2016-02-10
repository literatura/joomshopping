<?php
defined('_JEXEC') or die('Restricted access');

class plgJshoppingAdminjdownloads_files_for_product extends JPlugin
{
	var $sep1 = "{jdownloads_cat_id}";
	var $sep2 = "{/jdownloads_cat_id}";
	
	function __construct(&$subject, $config){
		parent::__construct($subject, $config);

	}

	function onBeforeDisplaySaveProduct(&$post, &$product) {
		$_lang = JModelLegacy::getInstance('Languages','JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
            //$post['description_'.$lang->language] .= $this->sep1 . JRequest::getVar('jdownloads_cat_id_'.$lang->id,'','post',"string", 2) . $this->sep2;
            $post['description_'.$lang->language] = $this->sep1 . JRequest::getVar('jdownloads_cat_id_'.$lang->id,'','post',"string", 2) . $this->sep2 . $post['description_'.$lang->language];
        }

	}
	
	function onBeforeDisplayEditProduct(&$product, &$related_products, &$lists, &$listfreeattributes, &$tax_value) {
		//die("123");
		$_lang = JModelLegacy::getInstance('Languages','JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
			/*$tmp = explode($this->sep, $product->{'description_'.$lang->language});
			if (isset($tmp[1])) {
				$product->{'description_'.$lang->language} = $tmp[0];
				$product->{'jdownloads_cat_id_'.$lang->language} = $tmp[1];
			} else {
				$product->{'jdownloads_cat_id_'.$lang->language} = '';
			}*/

			$start = mb_strpos($product->{'description_'.$lang->language}, $this->sep1);
			$end = mb_strpos($product->{'description_'.$lang->language}, $this->sep2);

			if($end!=false){				
				$product->{'jdownloads_cat_id_'.$lang->language} = mb_substr($product->{'description_'.$lang->language}, ($start+mb_strlen($this->sep1)), ($end-$start-mb_strlen($this->sep1)));
				$product->{'description_'.$lang->language} = mb_substr($product->{'description_'.$lang->language}, ($end+mb_strlen($this->sep2)));
				//echo $product->{'description_'.$lang->language}; die();
			}else{
				$product->{'jdownloads_cat_id_'.$lang->language} = '';
			}

			

        }
	}
	
	function onBeforeDisplayEditProductView(&$view) {
		//die("567");
		$_lang = JModelLegacy::getInstance('Languages','JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
			if (isset($view->product->{'jdownloads_cat_id_'.$lang->language})) {
				if (!isset($view->{'plugin_template_description_'.$lang->language})) $view->{'plugin_template_description_'.$lang->language} = '';
				$jdownloads_cat_id = "jdownloads_cat_id_".$lang->language;
				$view->{'plugin_template_description_'.$lang->language} .= '<tr><td class="key">ID категории jDownloads</td><td><input type="text" name="jdownloads_cat_id_'.$lang->id.'" value="'.$view->product->$jdownloads_cat_id.'" /></td></tr>';

				/*$editor->display('second_description'.$lang->id,  $view->product->$jdownloads_cat_id , '100%', '350', '75', '20' )*/
			}
		}
	}

}
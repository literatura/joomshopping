<?php
	$name = 'jDownloads files for JoomShopping';
	$type = 'plugin';
	$element = 'jdownloads_files_for_product';
	$folders = array('jshoppingproducts', 'jshoppingadmin');
	$version = '1.0.1';
	$cache = '{"creationDate":"07.10.2015","author":"Niyaz Musin","authorEmail":"ya@ya.ru","authorUrl":"http://ya.ru","version":"'.$version.'"}';
	$params = '{}';

	$db = JFactory::getDbo();
	foreach($folders as $folder){
		$db->setQuery("SELECT `extension_id` FROM `#__extensions` WHERE `element`='".$element."' AND `folder`='".$folder."'");
		$id = $db->loadResult();
		if(!$id) {
			$query = "INSERT INTO `#__extensions`(`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`) VALUES
			('".$name."', '".$type."', '".$element."', '".$folder."', 0, 1, 1, 0,'".addslashes($cache)."','".addslashes($params)."')";
		} else {
			$query = "UPDATE `#__extensions` SET `name`='".$name."', `manifest_cache`='".addslashes($cache)."', `params`='".addslashes($params)."' WHERE `extension_id`=".$id;
		}
		$db->setQuery($query);
		$db->query();
	}
    
	
	$addon = JTable::getInstance('addon', 'jshop');
	$addon->loadAlias($element);
	$addon->set('name', $name);
	$addon->set('version',$version);
	$addon->set('uninstall','/components/com_jshopping/addons/'.$element.'/uninstall.php');
	$addon->store();
?>
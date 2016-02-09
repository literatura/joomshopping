<?php
	defined('_JEXEC') or die('Restricted access');
	$db = JFactory::getDbo();
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE element = 'jdownloads_files_for_product' AND folder = 'jshoppingproducts' AND `type` = 'plugin'");
	$db->query();
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE element = 'jdownloads_files_for_product' AND folder = 'jshoppingadmin' AND `type` = 'plugin'");
	$db->query();
	
	jimport('joomla.filesystem.folder');
	foreach(array(
        'plugins/jshoppingproducts/jdownloads_files_for_product/',
		'plugins/jshoppingadmin/jdownloads_files_for_product/',
		'components/com_jshopping/addons/jdownloads_files_for_product/'
	) as $folder){JFolder::delete(JPATH_ROOT.'/'.$folder);}
?>
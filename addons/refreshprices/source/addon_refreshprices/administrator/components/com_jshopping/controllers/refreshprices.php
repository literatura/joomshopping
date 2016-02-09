<?php
/**
* @version      1.1.1 06.10.2011
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class JshoppingControllerRefreshprices extends JControllerLegacy{
    
    function __construct( $config = array() ){
        JSFactory::loadExtLanguageFile('refreshprices');
        parent::__construct( $config );
        addSubmenu("other");
    }

    function display($cachable = false, $urlparams = false){ 
        $db = JFactory::getDBO();
        $view=$this->getView("refreshprices", 'html');      
        $view->display();
    }

    function loadfile(){
        
        $mainframe =& JFactory::getApplication(); 
        
        $files =  $this->input->files->get('xlsfile');

        if(  !empty($files)){
            
            $src = $files['tmp_name'];

            // Получаем расширение загруженного файла
            $ext = JFile::makeSafe($files['name']);
            $ext = JFile::getExt($ext);

            $filename = "price.".$ext;

            $dest = JPATH_ROOT .DS. "images".DS."refreshprices".DS. $filename;
            if ( !JFile::upload($src, $dest) ) 
            {                 
                // Выводим ошибку
                $mainframe->redirect("index.php?option=com_jshopping&controller=refreshprices&task=view", _JSHOP_FILENOTUPLOADED, 'error');
            }

            $mainframe->redirect("index.php?option=com_jshopping&controller=refreshprices&task=view&file=1", _JSHOP_FILEUPLOADED);
        }else{
            $mainframe->redirect("index.php?option=com_jshopping&controller=refreshprices&task=view", _JSHOP_FILENOTSELECTED, 'error');
        }
       
    }

    // Предпросмотр данных
    function preload(){
        $jinput = JFactory::getApplication()->input;
        // Проверяем есть ли файл
        $dest = JPATH_ROOT .DS. "images".DS."refreshprices".DS;
        $filename = "price.xls";

        if(!JFile::exists($dest.$filename)){
            if(JFile::exists($dest.$filename."x")){
                $filename = "price.xlsx";
            }else{
                // Выводим ошибку
                echo new JResponseJson(null, _JSHOP_FILENOTUPLOADED, true);
                return;
            }
        }    

        require_once JPATH_LIBRARIES.DS.'excelreader'.DS.'excel_reader2.php';
        $data = new Spreadsheet_Excel_Reader($dest.$filename, false);

        //echo "<pre>"; var_dump($data->boundsheets); die();
         // Получаем номер листа
        $sheet = $jinput->get('sheet', 0, 'UINT');
        if($sheet >= count($data->boundsheets)){
            // выводим ошибку
            echo new JResponseJson(null, _JSHOP_WRONGSHEETINDEX, true);
            return;
        }

        // Получаем первые 10 строк из таблицы первого листа
        $cols = $data->colcount($sheet);

        $result = array();

        // Если уже были отправлены данные о листах, то не отправляем
        if($sheet == 0){
            $result['sheets_count'] = count($data->boundsheets);
            $result['sheets'] = $data->boundsheets;
        }

        $result['cols_count'] = $cols;
       
        $result['items'] = array();

        for($j=0; $j<=15; $j++){
            for($i=0; $i<$cols; $i++){
            
                $result['items'][$j][$i] = $data->val($j, $i, $sheet);
            }
        }
        
        echo new JResponseJson($result, null, false);
        
    }

    // Обновление данных по ценам в БД
    function refreshprices(){
        $jinput = JFactory::getApplication()->input;
        $db = JFactory::getDbo();
        require_once JPATH_LIBRARIES.DS.'excelreader'.DS.'excel_reader2.php';

        // Проверяем есть ли файл
        $dest = JPATH_ROOT .DS. "images".DS."refreshprices".DS;
        $filename = "price.xls";

        if(!JFile::exists($dest.$filename)){
            if(JFile::exists($dest.$filename."x")){
                $filename = "price.xlsx";
            }else{
                // Выводим ошибку
                echo new JResponseJson(null, _JSHOP_FILENOTUPLOADED, true);
                return;
            }
        }   

        $data = new Spreadsheet_Excel_Reader($dest.$filename, false);

        // Получаем номер листа
        $sheet = $jinput->get('sheet', 0, 'UINT');
        if($sheet >= count($data->boundsheets)){
            // выводим ошибку
            echo new JResponseJson(null, _JSHOP_WRONGSHEETINDEX, true);
            return;
        }

        // Получаем количество строк из таблицы первого листа
        $rows = $data->rowcount($sheet);
        $cols = $data->colcount($sheet);

        // Поулчаем номера столбцов для артикула, названия и цены
        $col_articul = $jinput->get('artcol', 0, 'UINT');;
        $col_price = $jinput->get('pricecol', 0, 'UINT');;
        $col_name = $jinput->get('namecol', 0, 'UINT');;

        if($col_articul == $col_price || $col_articul == $col_name || $col_name == $col_price){
            // Выводим ошибку
            echo new JResponseJson(null, _JSHOP_NOTSELECTEDCOLS, true);
            return;
        }

        if($col_articul >= $cols || $col_name >= $cols || $col_price >= $cols){
             // Выводим ошибку
            echo new JResponseJson(null, _JSHOP_WRONGDCOLS, true);
            return;
        }        

        if($rows == 0 || $cols < 3){
            // Выводим ошибку
            echo new JResponseJson(null, _JSHOP_WRONGFILEDATA, true);
            return;
        }

        $result = array();
        $result['log'] = array();        

        // перебираем все строки
        // ищем артикул 
        // проверяем указана ли цена 
        // Если да, то делаем запрос для обновления цены в БД

        for($j=0; $j<=$rows; $j++){
            $tmparray = array();
            $msg=""; // ошибок нет
            $msg_type = 0; //0 - нет ошибок, 1 -предупреждение, 2 - ошибка, важно

            $cur_data = $data->val($j, $col_articul, $sheet);
            
            if($cur_data != "" && is_int($cur_data)){
                $cur_price = $data->val($j, $col_price, $sheet);

                // добавляем в лог
                $tmparray['row'] = $j; // номер строки
                $tmparray['art'] = $cur_data; // артикул
                $tmparray['name'] = $data->val($j, $col_name, $sheet); // название
                $tmparray['price'] = $cur_price; // цена

                if($cur_price!="" && is_numeric($cur_price)){
                    $query = $db->getQuery(true);
                    // Fields to update.
                    $fields = array(
                        $db->quoteName('product_price') . ' = ' . floatval($cur_price),
                        $db->quoteName('min_price') . ' = '. floatval($cur_price)
                    );
                     
                    // Conditions for which records should be updated.
                    $conditions = array(
                        $db->quoteName('product_ean') . ' = '.intval($cur_data)                        
                    );
                     
                    $query->update($db->quoteName('#__jshopping_products'))->set($fields)->where($conditions);
                     
                    $db->setQuery($query);


                    
                    try {
                        $res = $db->execute();
                
                        // Значение не было фактически обновлено. 
                        // Нужно проверить есть ли товар с таким артикулом
                        if ($db->getAffectedRows() == 0){
                            $query = $db->getQuery(true);

                            $query
                                ->select(array('COUNT(*)'))
                                ->from($db->quoteName('#__jshopping_products'))
                                ->where($db->quoteName('product_ean') . ' = '. intval($cur_data));

                            $db->setQuery($query);

                            $res = $db->loadResult();

                            if($res == 0){
                                $msg = _JSHOP_PRODUCTNOTFOUND;
                                $msg_type = 1;
                            }

                            
                        }
                        
                    }
                    catch (Exception $e) {
                        // Catch the error.
                        $msg = _JSHOP_DBERROR; // сообщение об ошибке
                        $msg_type = 2;
                    }                   
                    
                }else{
                    // Есть артикул, но не указана цена
                    $msg = _JSHOP_WRONGPRICE_FIELD; // сообщение   
                    $msg_type = 1;             
                }

                

                $tmparray['msg'] = $msg;
                $tmparray['msg_type'] = $msg_type;

                $result['log'][] = $tmparray;            
            }

            
        }
        
        echo new JResponseJson($result, null, false);
    }

    function refresh(){
        $mainframe = JFactory::getApplication(); 


        $mainframe->redirect("index.php?option=com_jshopping&controller=refreshprices&task=view", _JSHOP_COMPLETED);
    }

}
?>
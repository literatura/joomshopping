<?php
displaySubmenuOptions();
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base()."components/com_jshopping/css/refreshprices.css");
$doc->addScript(JURI::base()."components/com_jshopping/js/refreshprices.js");

$fileuploaded = false;
$cl = "";
$jinput = JFactory::getApplication()->input;
if($jinput->getInt('file', 0) == 1){
	$fileuploaded = true;
	$cl="completed";
}

?>
<h2><?php echo _JSHOP_LABEL_TITTLE ?></h2>
<div id="uploadcontainer" class="<?php echo $cl ?>">
	<h3><?php echo  _JSHOP_LABEL_UPLOADTITLE?></h3>
<form action = "index.php?option=com_jshopping&controller=refreshprices&task=loadfile" method = "post" name = "adminForm" enctype = "multipart/form-data">        
     <input type = "hidden" name = "hidemainmenu" value = "0" />
     <input type = "hidden" name = "boxchecked" value = "0" />  
     
      <fieldset class="adminform" >
     <label style="font-size:14px;"><?php echo  _JSHOP_LABEL_UPLOAD?></label>
     <input name="xlsfile" type="file" />
     <button type="submit" name="upload" class = "button"><?php echo _JSHOP_LABEL_UPLOADBUTTON?></button>
     </fieldset>        
</form>
</div>
<?php if($fileuploaded){ ?>
<div id="refreshcontainer">
	<h3><?php echo  _JSHOP_LABEL_REFRESHTITLE?></h3>
	<div>
		<button id="showintro" name="showintro"  class = "button"><?php echo _JSHOP_LABEL_PRELOADBUTTON?></button>
		<button id="startrefresh" name="startrefresh"  class = "button"><?php echo _JSHOP_LABEL_STARTBUTTON?></button>
		<label><?php echo _JSHOP_LABEL_SHEETSELECT?></label>
		<select id="sheetselect" name="sheetselect" style="display: none;"></select>
		<label><?php echo _JSHOP_LABEL_ARTSELECT?></label>
		<select id="artselect" name="artselect" class="colsetting"></select>
		<label><?php echo _JSHOP_LABEL_NAMESELECT?></label>
		<select id="nameselect" name="nameselect" class="colsetting"></select>
		<label><?php echo _JSHOP_LABEL_PRICESELECT?></label>
		<select id="priceselect" name="priceselect" class="colsetting"></select>
	</div>
	<div id="preview"></div>
</div>	

<?php } ?>
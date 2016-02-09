jQuery(document).ready(function()
{
	jQuery("#showintro").click(function(){
		jQuery.ajax({
			type: 		"POST",
			url: 		"http://"+document.domain+"/administrator/index.php?option=com_jshopping&controller=refreshprices&task=preload&format=raw",
			data:       {
							'sheet' : jQuery("#sheetselect").val()
						},
			dataType: 	"json",
			async: 		false,
			success: function(response){
				if (!response.success && response.message){
					alert(response.message);
				}else{
					var $container = jQuery("#preview");
					$container.empty();
					var $table = jQuery("<table></table>").appendTo($container);

					for (var i = 0; i < response.data.items.length; i++) {
						if(i===0){
							var $trh = jQuery("<tr></tr>").appendTo($table);
						}

					    var $tr = jQuery("<tr></tr>").appendTo($table);
					    for(var j = 0; j < response.data.items[i].length; j++){
					    	if(i===0){
					    		jQuery("<th>" + j + "</th>").appendTo($trh);
					    	}
					    	jQuery("<td>" + response.data.items[i][j] + "</td>").appendTo($tr);
					    }
					}

					if('sheets_count' in response.data){
						var $select = jQuery("#sheetselect");
						$select.empty();
						for (var i = 0; i < response.data.sheets_count; i++) {
							jQuery('<option value="'+ i +'">'+ response.data.sheets[i].name +'</option>').appendTo($select);
						};

						$select.show();
					}

					var opt = '';
					for (var i = 0; i < response.data.cols_count; i++) {
						opt+='<option value="'+ i +'">'+ i +'</option>';
					};

					jQuery("#artselect").empty().append(opt).show();
					jQuery("#nameselect").empty().append(opt).show();
					jQuery("#priceselect").empty().append(opt).show();
					
				}
			}
		});//$.ajax({	
	});

	jQuery("#startrefresh").click(function(){
		var art = jQuery("#artselect").val();
		var name = jQuery("#nameselect").val();
		var price = jQuery("#priceselect").val();

		if(art == name || art == price || name == price){
			alert("Номера столбцов не должны совпадать");
			return;
		}

		jQuery.ajax({
			type: 		"POST",
			url: 		"http://"+document.domain+"/administrator/index.php?option=com_jshopping&controller=refreshprices&task=refreshprices&format=raw",
			data:       {
							'sheet' : jQuery("#sheetselect").val(),
							'artcol' : art,
							'namecol' : name,
							'pricecol' : price
						},
			dataType: 	"json",
			async: 		false,
			success: function(response){
				if (!response.success && response.message){
					alert(response.message);
				}else{
					var $container = jQuery("#preview");
					$container.empty();
					var $table = jQuery("<table></table>").appendTo($container);
					jQuery("<tr><th>Номер строки</th><th>Код товара</th><th>Название</th><th>Цена</th><th>Сообщение</th></tr>").appendTo($table);

					for (var i = 0; i < response.data.log.length; i++) {
					    var $tr = jQuery("<tr></tr>").appendTo($table);					    					    
					    jQuery("<td>" + response.data.log[i]['row'] + "</td>").appendTo($tr);
					    jQuery("<td>" + response.data.log[i]['art'] + "</td>").appendTo($tr);
					    jQuery("<td>" + response.data.log[i]['name'] + "</td>").appendTo($tr);
					    jQuery("<td>" + response.data.log[i]['price'] + "</td>").appendTo($tr);
					    jQuery("<td>" + response.data.log[i]['msg'] + "</td>").appendTo($tr);	
					    switch(response.data.log[i]['msg_type']){
					    	case 0:
					    		break;

					    	case 1:
					    		$tr.addClass('log_warning');
					    		break;

					    	case 2:
					    		$tr.addClass('log_error');
					    		break;
					    }			    
					}
				}
			}
		});//$.ajax({	
	});


});
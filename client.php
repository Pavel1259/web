<?$db_host   = 'localhost';
$db_user   = 'pasha';
$db_pass   = '643105';
$db_database = "web_technology";

$link = mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_database,$link);
mysql_select_db($db_database,$link) or die("Нет соединения с БД".mysql_error());
mysql_query("SET NAMES utf8");
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Клиент</title>
<script src="jquery-1.7.2.min.js"></script>
<script type="text/JavaScript">
var socket;
var new_name_user;
var new_password_user;
// статус пользователя сделать через сокеты
var t;
var select_id_case = 1;
var select_id_skin = 0;
	///////////////////////////////////////////////
function sleep(milliseconds) {    //переделать на джаву
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
        }
    }
}
	//////////////////////////////////////////
function close_banner_1(){
	jQuery('.banner div').remove();
}
function close_banner_get_skin(){
	jQuery('.get_skin').remove();
}
function update_info_person()
{
	var user_data = new_name_user + ',' + new_password_user;
						
	jQuery.ajax({
            url: "for_db.php",
            type: "POST",
            data: {mode:5, parametr: user_data}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
					if(result.code_error == 1)
					{
						alert(result.errors);
					}
					else{
						
						document.querySelector(".status_person_name").textContent = "Пользователь: " + result.users['name_person'][0];
						document.querySelector(".status_person_currency").textContent ="Балланс: " + result.users['currency'][0];
						jQuery('.status_person_inventory div').remove();
						jQuery('.status_person_inventory').append(function(){
							var res = '<div style="position:absolute; background-image: url(img/background/background-6.jpg);">';
							for(var i = 0; i < result.users['inventory_id'].length; i++){
								res += "<div style='width:100px; float:left; margin-left:10px;'><img src='"+result.users["inventory_img"][i]+"' width='100px' />";
								res += "<a href='#' onClick='select_id_skin="+result.users["inventory_id"][i]+";sell_skin_function();'>";
								res += "<p style='height:70px;'>" + result.users["inventory_name"][i] + "</p><p>Цена: "+ result.users['inventory_price'][i] +"</p></a></div>";
							}
							res+="<div style='clear:both;'></div></div>";
							return res;
						});
						console.log(result);
					}
                }else{
                    alert(result.message);
                }
				return false;
            }
    });
}
function update_page(){
	// обновлять кейсы 
	
	jQuery.ajax({
            url: "for_db.php",
            type: "POST",
            data: {mode:2, parametr: null}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
					jQuery('.show_case div').remove();
                    jQuery('.show_case').append(function(){
						console.log(result);
						var res = '';
						for(var i = 0; i < result.users['name'].length; i++){
							console.log("123");
								res += "<div style='float:left; width:100px; margin-left:10px;'>";
								res += "<a href='#' onClick='select_id_case="+result.users["id"][i]+";select_case();'>";
								res += "<img src='"+result.users["img"][i]+"' width='100px' />"+ result.users["name"][i] + " "+ result.users['price'][i] +"</a></div>";
						}
							return res;
					});
					console.log(result);
                }else{
                    alert(result.message);
                }
				return false;
            }
    });
}
function select_case(){
	$('.status_person_inventory').css('display','none');
	jQuery.ajax({
            url: "for_db.php",
            type: "POST",
            data: {mode:3, parametr:select_id_case}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
					jQuery('.banner div').remove();
                    jQuery('.banner').append(function(){
						t = result;
						console.log(result);
						var res = '<div style="position: absolute; left:10%; top:100px; heigth:500px; width:80%; background-image: url(img/background/background-6.jpg); z-index:2;" class="block_fixed">';
						res += '<input type="button" name="close_banner_1" value="Закрыть" onClick="close_banner_1();"';
						res += '<div><h2 align="center">'+result.users["name"][0]+'</h2>';
						res += '<img src="'+result.users["img"][0]+'" width="20%" style="margin:0 40% 0 40%;" />';
						res += '<div style="position:absolute; left:50%; margin-left:-55px;"><span style="margin-right:20px; margin-bottom:20px;">'+result.users["price"][0]+' ед. </span><input type="button" name="open_case_button" value="Открыть" onClick="var select_id_case ='+select_id_case+';open_case_function();"></div>';
						return res;
					});
					console.log(result);
			jQuery.ajax({
            url: "for_db.php",
            type: "POST",
            data: {mode:1,parametr:select_id_case}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
                    jQuery('.block_fixed').append(function(){
						t = result;
						console.log(result);
						var res = '';
						res += '<div style="clear:both; margin-top:40px;">';
						for(var i = 0; i<result.users["name"].length;i++)
						{
							res += '<div style="width:100px;float:left; margin-left:10px;">';
							res += '<img src="'+result.users["img"][i]+'" width="100px"/>';
							res += '<h3 style="height:75px;">'+result.users["name"][i]+'</h3></div>';
						}
						res += '</div>';
						return res;
					});
					console.log(result);
                }else{
                    alert(result.message);
                }
				return false;
            }
    });
                }else{
                    alert(result.message);
                }
				return false;
            }
    });
	//sleep(500);
	
	
	
}

function open_case_function(){
	var message = {
			username: new_name_user,
			password: new_password_user,
			currency: 0,
			id_skins: "",
			mode: "open",
			select_id_case: select_id_case,
			select_id_skin: 1,
			output: 1,
			message: 1
		}
		socket.send(JSON.stringify(message));
		return false;
}
function sell_skin_function(){
	var message = {
			username: new_name_user,
			password: new_password_user,
			currency: 0,
			id_skins: "",
			mode: "sell",
			select_id_case: 1,
			select_id_skin: select_id_skin,
			output: 1,
			message: 1
		}
		socket.send(JSON.stringify(message));
		return false;
}
</script>
</head>
<body>

<table class="rows">
<script>update_page();</script>
</table>
<table>
<tr>
	<td>
		<div>
			<p href="index.php" class="status_person_name">Пользователь:</p><a href="index.php">Выйти</a>
			<p class="status_person_currency">Баланс:</p>
		</div>
		<p><a href="#" onClick="close_banner_1();$('.status_person_inventory').css('display','block');">Инвентарь:</a></p>
		<div class="status_person_inventory" style="display:none; position:absolute; left:10%; top:100px; rigth:150px; width:80%; background-image: url(img/background/background-6.jpg); z-index:3;">
			<p style="background-image: url(img/background/background-6.jpg);"><input type="submit" value="Закрыть" onClick="$('.status_person_inventory').css('display','none');" ></p>
		</div>
		<div id="status"></div>
		
		
	</td>
	<td>
		<div class="show_case" style="position:absolute; left:10%; top:175px; rigth:150px; width:80%; background-color:№cccccc; z-index:1;">
			
		</div>
	</td>
</tr>
</table>

<div class="banner" style="z-index:2;">
	</div>
</div>
<script>
window.onload = function(){
	
	// достаем данные пользователя 
	var split_url = decodeURIComponent(location.search.substr(1)).split('?');
	var split_result= split_url[0].split('&');
	new_name_user = split_result[0];
	new_password_user = split_result[1];
	
	// проверяем
	socket = new WebSocket("ws://localhost:8080");
	var status = document.querySelector("#status");
	// открытие соединения
	socket.onopen = function(){
		status.innerHTML = "соединение установлено";
	}
	update_info_person();
	
	// закрытие соединения
	socket.onclose = function(event){
		if(event.wasClean){
			console.log("соединение закрыто");
		}else{
			console.log("соединение как-то закрыто");
		}
		console.log("код" + event.code + " причина: " + event.reason);
	}
	
	// получение данных
	socket.onmessage = function(){
		var message = JSON.parse(event.data);
		console.log(message);
		
		// код ошибки
		// 1 - нет такого пользователя
		// 2 - не достаточно средств на счету
		// 3 - такого кейса не существует
		// 4 - кейс открыт
		// 5 - товар продан
		// 6 - состояние счета и какие есть выигранные предметы
		
		if(message.output == 1){ // пользователя с такими данными не существует
			alert("Пользователя с такими данными не существует!");
		}
		else if(message.output == 2){ // недостаточно средств на счету
			alert(message.message);
		}
		else if(message.output == 3){ // такого кейса не существует
			alert(message.message);
		}
		else if(message.output == 4){ // кейс открыт
			jQuery.ajax({
            url: "for_db.php",
            type: "POST",
            data: {mode:4, parametr: message.id_skins}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){  
                    jQuery('.banner').append(function(){
						var res = '<div class="get_skin" style="position: fixed; left:150px; top:150px; heigth:500px; width:1000px; background-image:url(img/background/background-2.jpg); z-index:4;" class="block_fixed">';
						res += '<img src="'+result.users["img"][0]+'" width="50%" style="margin:0 25% 0 25%;"/>';
						res += '<p style="color:white; text-align:center;">'+result.users["name"][0]+'</p>'
						res += '<input type="submit" value="Закрыть" style="position:absolute; right:0px; top:10px;" onClick="close_banner_get_skin();"></div>';
						res += '</div>';
						return res;
					});
					console.log(result);
                }else{
                    alert(result.message);
                }
				return false;
            }
			});
		}
		else if(message.output == 5){ // товар продан
			// сообщение
		}
		else if(message.output == 6){ // такого товара у вас нет
			alert(message.message);
		}
		
		update_info_person();
		status.innerHTML = "Пришли даные: " + message.select_id_case + "<br>Message:" + message.message + "<br>Output:" + message.output + "<br>Skins:" + message.id_skins;
	}
	
	// возникновение ошибки
	socket.onerror = function(){
		status.innerHTML = "Ошибка: " + event.message;
	}
	// отправка
	jQuery(".take").bind("click", function() {
		var message = {
			name: "Hello!!!",
			msg: "Web"
		}
		socket.send(JSON.stringify(message));
		return false;
	});
}
</script>
</body>

</html>

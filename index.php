<html>
<head>
	<link rel="stylesheet" href="codemirror.min.css">
	<link id="cssTheme" rel="stylesheet" href="monokai.css">
	<!--script src="jquery-3.0.0.min.js"></script-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="codemirror.min.js"></script>
	<script src="javascript.js"></script>
	<script src="format.js"></script>
	<style>
		html,body{
			width: 100%;
		}
		table{
			width: 80%;
			margin: 10px auto;
		}
		td{
			padding: 3px 3px;
			border: solid 1px;
		}
		.input{
			text-align:left;
		}
		.input > input{
			width: 100%;
			padding: 3px 3px;
		}
		#text{
			resize: none;
			width: 100%;
			height: 150px;
		}
		.button_block{
			text-align: center;
		}
		.button_block > input,
		.button_block > button{
			width: 100%;
		}
		#response{
			height: 100%;
			width: 0;
		}
		.CodeMirror-wrap{
			width: 500px;
			height: 100%;
		}
		#responseTitle{
			text-align: center;
		}
		
		.chat_block,
		.from_block,
		.message_block,
		.general_block,
		.update_block{
			text-align:right;
		}
	</style>
</head>
<body>
	<table>
		<tr>
			<td colspan=3 class="button_block"><button onClick="javascript: randomForm();" >RANDOM</button></td>
			<td class="button_block" ><button id="send_button" onClick="javascript: sendForm();">SEND</button></td>
			<td id="responseTitle">RESPONSE</td>
		</tr>
		<tr>
			<td colspan=3 class="general_block">webhook:</td><td class="input"><input id="hook" value="http://127.0.0.1/TATBot/webhook.php" type="text" /></td>
			<td rowspan=19 id="response" ><textarea id="response_area"></textarea></td>
		</tr>
		<tr>
			<td colspan=3 class="update_block">update_id:</td><td class="input"><input id="update_id" type="text" /></td>
		</tr>
		<tr><td rowspan=17 class="message_block">message</td></tr>
		<tr><td colspan=2 class="message_block">message_id:</td><td class="input"><input id="message_id" type="text" /></td></tr>
		<tr><td colspan=2 class="message_block">date:</td><td class="input"><input id="date" type="text" /></td></tr>
		<tr><td rowspan=7 class="from_block">from:</td></tr>
		<tr><td class="from_block">id:</td><td class="input"><input id="from_id" type="text" /></td></tr>
		<tr><td class="from_block">is_bot:</td><td class="input"><input id="from_is_bot" type="checkbox" /></td></tr>
		<tr><td class="from_block">first_name:</td><td class="input"><input id="from_first_name" type="text" /></td></tr>
		<tr><td class="from_block">last_name:</td><td class="input"><input id="from_last_name" type="text" /></td></tr>
		<tr><td class="from_block">username:</td><td class="input"><input id="from_username" type="text" /></td></tr>
		<tr><td class="from_block">language_code:</td><td class="input"><input id="from_language_code" type="text" /></td></tr>
		<tr><td rowspan=6 class="chat_block">chat:</td></tr>
		<tr><td class="chat_block">id:</td><td class="input"><input id="chat_id" type="text" /></td></tr>
		<tr><td class="chat_block">first_name:</td><td class="input"><input id="chat_first_name" type="text" /></td></tr>
		<tr><td class="chat_block">last_name:</td><td class="input"><input id="chat_last_name" type="text" /></td></tr>
		<tr><td class="chat_block">username:</td><td class="input"><input id="chat_username" type="text" /></td></tr>
		<tr><td class="chat_block">type:</td><td class="input"><input id="chat_type" type="text" /></td></tr>
		<tr><td colspan=2 class="message_block">text:</td><td class="input"><textarea id="text" ></textarea></td></tr>
	</table>
	<script>
	
		var codEditor;
	
		function randomId(){
			return new Array(6).fill(0).map(()=>{
				return Math.floor(Math.random() * 10).toString()
			}).join('');
		}
		
		function randomFrom(){
			$('#from_first_name').val('pippo');
			$('#from_last_name').val('pluto');
			$('#from_username').val('clever');
			$('#from_language_code').val('it-IT');
		}
		
		function randomChat(){
			$('#chat_first_name').val('pippoChat');
			$('#chat_last_name').val('plutoChat');
			$('#chat_username').val('cleverChat');
			$('#chat_type').val('private');
		}
		
		function randomForm(){
			var fromId = randomId();
			$('#update_id').val(randomId());
			$('#message_id').val(randomId());
			$('#from_id').val(fromId);
			$('#chat_id').val(fromId);
			randomFrom();
			randomChat();
		}
		
		function sendForm(){
			$.post('request.php',{
				'update_id' : $('#update_id').val(),
				'message_id' : $('#message_id').val(),
				'date' : $('#date').val(),
				'from_id' : $('#from_id').val(),
				//'from_is_bot' : $('#from_is_bot').val(),
				'from_first_name' : $('#from_first_name').val(),
				'from_last_name' : $('#from_last_name').val(),
				'from_username' : $('#from_username').val(),
				'from_language_code' : $('#from_language_code').val(),
				'chat_id' : $('#chat_id').val(),
				'chat_first_name' : $('#chat_first_name').val(),
				'chat_last_name' : $('#chat_last_name').val(),
				'chat_username' : $('#chat_username').val(),
				'chat_type' : $('#chat_type').val(),
				'text' : $('#text').val(),
				'hook' : $('#hook').val(),
			},function(data){
				//$('#response > textarea').html(data);
			});
		}
		
		
		//codEditor.setOption('value',tmp);
		
		function checkResponse(){
			$.get('response.txt',{},(data)=>{
				console.log(data);
				//JSON.parse(data).text;
				if(codEditor.getValue() != JSON.parse(data).text){
					codEditor.setOption('value',JSON.parse(data).text);
				}
				setTimeout(()=>{
					checkResponse();
				},2500);
			});
		}
		
		$(document).ready(()=>{
			codEditor = CodeMirror.fromTextArea(document.getElementById("response_area"),{
				matchBrackets: true,
				autoCloseBrackets: true,
				mode: "application/json",
				lineWrapping: true,
				theme: 'monokai'
			});
			checkResponse();
		});
	</Script>
</body>
</html>
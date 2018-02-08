<?php
	//exec('start beep.bat');
	mb_internal_encoding('UTF-8');
	file_put_contents('../response.txt',json_encode($_GET));
?>
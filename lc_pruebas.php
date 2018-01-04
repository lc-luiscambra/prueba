<?php 

include ('config.php');

$connection = ssh2_connect(__SSH_SERVER__, __SSH_PORT__);

$authSuccess = ssh2_auth_password($connection, __SSH_USER__, __SSH_PWD__);

if (!$authSuccess) {
    throw new Exception('SSH authentication failure');
}
/* start a shell session*/
$shell = ssh2_shell($connection, 'xterm');
if ($shell === false) {
    throw new Exception('Failed to open shell');
}
/*die ('LC_TEST');*/
if (!function_exists('ssh2_shell')) { 
	die('No existe la funcion ssh2_connect.'); 
}else{
	die('Si existe la funcionalidad');
}
?>
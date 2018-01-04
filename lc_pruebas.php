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
/*if (!function_exists('ssh2_shell')) { 
	die('No existe la funcion ssh2_connect.'); 
}else{
	die('Si existe la funcionalidad');
}*/

stream_set_blocking($shell, true);
stream_set_timeout($shell, 20);

/* run the commands*/
$output = '';
$endSentinel = "!~@#_DONE_#@~!";
fwrite($shell, 'cd '.__SSH_ROUTE__ . "\n");
fwrite($shell, 'git pull' . "\n");
fwrite($shell, 'echo ' . escapeshellarg($endSentinel) . "\n");
die($shell);
while (true) {
    $o = stream_get_contents($shell);
    if ($o === false) {
        die('Failed while reading output from shell');
    }
    $output .= $o;
    die($output);
    if (strpos($output, $endSentinel) !== false) {
        break;
    }
}
fclose($shell);
fclose($connection);
die('OK');
?>
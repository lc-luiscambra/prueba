<?php
/**
 * deploy.php by Hayden Schiff (oxguy3)
 * Available at https://gist.github.com/oxguy3/70ea582d951d4b0f78edec282a2bebf9
 * 
 * No rights reserved. Dedicated to public domain via CC0 1.0 Universal.
 * See https://creativecommons.org/publicdomain/zero/1.0/ for terms.
 */
 
include ('config.php');
/**
 * Convenience function for sending emails
 *
 * If you want to disable email sending, just replace the content of this
 * function with "return true;".
 */
function sendEmail($success, $message)
{
    /*$headers = 'Content-type: text/plain' . "\r\n" .
        'From: david@'.SITE_DOMAIN;
    $subject = '['.SITE_DOMAIN.'] ';
    if ($success) {
        $subject .= 'Deploy success';
    } else {
        $subject .= 'Deploy failure';
        $headers .= "\r\n" .
            'X-Priority: 1 (Highest)' . "\r\n" .
            'X-MSMail-Priority: High' . "\r\n" .
            'Importance: High';
    }
    return mail(
        EMAIL_RECIPIENT,
        $subject,
        $message,
        $headers
    );*/
    return true;
}
try {
    
    
    /* ssh into the local server*/
    
    $connection = ssh2_connect(__SSH_SERVER__, __SSH_PORT__);
    /*die ('LC_TEST');*/
    $authSuccess = ssh2_auth_password($connection, __SSH_USER__, __SSH_PWD__);
    
    /*die ('LC_TEST'.' - '.__SSH_SERVER__.' : '.__SSH_PORT__);*/
    /*$sshSession = ssh2_connect(__SSH_SERVER__, __SSH_PORT__);*/
    /*die ('LC_TEST');*/
    /*$authSuccess = ssh2_auth_pubkey_file(
        $sshSession,
        __SSH_USER__,
        '/'.__SSH_USER__.'/.ssh/'.__KEYPAIR_NAME__.'.pub',
        '/'.__SSH_USER__.'/.ssh/'.__KEYPAIR_NAME__,
        __KEYPAIR_PASSPHRASE__
    );*/
    
    if (!$authSuccess) {
        throw new Exception('SSH authentication failure');
    }
    /* start a shell session*/
    $shell = ssh2_shell($connection, 'xterm');
    if ($shell === false) {
        die('Failed to open shell');
    }
    stream_set_blocking($shell, true);
    stream_set_timeout($shell, 20);
    /* run the commands*/
    $output = '';
    $endSentinel = "!~@#_DONE_#@~!";
    fwrite($shell, 'cd '.__SSH_ROUTE__ . "\n");
    fwrite($shell, 'git pull' . "\n");
    fwrite($shell, 'echo ' . escapeshellarg($endSentinel) . "\n");
    /*die($shell);*/
    /*die(stream_get_contents($shell));*/
    while (true) {
        $o = stream_get_contents($shell);
        if ($o === false) {
            die('Failed while reading output from shell');
        }
        $output .= $o;
        if (strpos($output, $endSentinel) !== false) {
            break;
        }
    }
    fclose($shell);
    fclose($connection);
    $mailBody = "GitHub payload:\r\n"
        . print_r($data, true)
        . "\r\n\r\n"
        . "Output of `git pull`:\r\n"
        . $output
        . "\r\n"
        . 'That\'s all, toodles!';
    $mailSuccess = sendEmail(true, $mailBody);
} catch (Exception $e) {
    $mailSuccess = sendEmail(false, strval($e));
}
if(!$mailSuccess) {
    header('HTTP/1.0 500 Internal Server Error');
    die('Failed to send email to admin!');
}
die("All good here!");
?>
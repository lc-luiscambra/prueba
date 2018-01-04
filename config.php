<?php
define('__SSH_USER__', 'root');

define('__SSH_PWD__', 'kqQ-0J@erJmY!');

define('__SSH_SERVER__', '185.45.73.200');

define('__SSH_PORT__', 50050);

define('__SSH_ROUTE__', '/var/www/vhosts/dns73200.phdns12.es/httpdocs/pruebagit/');

/* random string of characters; must match the "Secret" defined in your GitHub webhook*/
define('__GITHUB_SECRET__', 'LCdeploy');
/* name of the git branch that you're deploying*/
define('__GITHUB_BRANCH__', 'master');
/* your email address, where you'll receive notices of deploy successes/failures*/
define('__EMAIL_RECIPIENT__', 'david@luiscambra.com');
/*/* domain of your website*/
define('__SITE_DOMAIN__', 'luiscambra.com');
/* filename for the keypair to use -- no need to change this if you follow the readme instructions*/
define('__KEYPAIR_NAME__', 'deploy');
/* the passphrase for your keypair*/
define('__KEYPAIR_PASSPHRASE__', 'luiscambra');
/* END OF CONFIGURATION OPTIONS*/

?>

<?php 

require '../src/client.php'; 

use mksms\Contact;
use mksms\Message;
use mksms\Client;

?>

<!DOCTYPE html>
<html>
<head>
	<title>PHP Lib for MKsms Test</title>
</head>
<body>
<?php

$API_KEY = "";
$API_HASH = "":

$jef = new Contact("000000000", "You");

echo print_r($jef->to_array());
echo "<br>";
echo $steph;
echo "<br>";

$msg1 = new Message($jef, "Enfin un message donne", Message::$OUT, false);
echo $msg1;

$client = new Client($API_KEY, $API_HASH);
$res = $client->send_message($msg1);
echo $res;
echo "<br>";
echo "<br>";

$res = $client->start_verify("000000000", "TotoService");
echo $res;
echo "<br>";
echo "<br>";

$res = $client->confirm_verify("000000000", "12345");
echo $res;
echo "<br>";
echo "<br>";

$res = $client->start_verify("000000000", "TotoService");
echo $res;
echo "<br>";
echo "<br>";

$res = $client->confirm_verify("000000000", "123457");
echo $res;
echo "<br>";
echo "<br>";

$res = $client->get_messages();
echo $res;
echo "<br>";
echo "<br>";


?>
</body>
</html>
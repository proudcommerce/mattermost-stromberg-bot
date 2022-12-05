<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use ThibaudDauce\Mattermost\Mattermost;
use ThibaudDauce\Mattermost\Message;
use ThibaudDauce\Mattermost\Attachment;

# config START
$mmWebhookUrl = 'https://YOUR_URL/hooks/YOUR_WEBHOOK_ID';
$mmChannel = 'YOUR_CHANNEL';
$mmUserName = 'Stromberg des Tages';
$mmIcon = 'https://www.proudsourcing.de/images/stromberg2.jpeg';
# config END

$ch = curl_init();
$churl='https://www.stromberg-api.de/api/quotes/random';
curl_setopt($ch, CURLOPT_URL, $churl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-type: application/json",
	"charset: utf-8",
	"Accept: application/json"
));
$json = curl_exec($ch);
$data = json_decode($json);
$text = $data->quote;

if($text != '') {
	$mattermost = new Mattermost(new Client, $mmWebhookUrl);
	$message = (new Message)
		->text('')
		->channel($teatime)
		->username($mmUserName)
		->iconUrl($mmIcon)
		->attachment(function (Attachment $attachment) {
			global $text;
			$attachment->info()->text($text);
		});

	$mattermost->send($message);
}
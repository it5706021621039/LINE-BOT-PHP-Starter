<?php
$access_token = 'kCz7tuLk8Ox8ptZvYvLn0tB5dIZKkfQwFceq5SVNVlst6gkBC5R6N8pnxEcyp4oZASv1VeAMXO9d7zgx7FHV53qViU7G/4V1lRdTbFvg7aDxHNTFLDZYKBXO2STB6FhrYU07LyObdn3rQ14qbxe1kAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		if ($event['type'] == 'message' && $event['message']['type'] == 'sticker') {
			$replyToken = $event['replyToken'];
			$messages = [
				'type' => 'sticker',
				'packageId'=> '1',
  			'stickerId'=> '1'
			];
		}
		if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
			$replyToken = $event['replyToken'];
			$messages = [
				'type'=> 'template',
  'altText'=> 'this is a buttons template',
  'template'=>{
      'type'=> 'buttons',
      'thumbnailImageUrl'=> 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/A_small_cup_of_coffee.JPG/275px-A_small_cup_of_coffee.JPG',
      'title'=> 'Menu',
      'text'=> 'Please select',
      'actions'=> [
          {
            'type'=> 'postback',
            'label'=> 'Buy',
            'data'=> 'action=buy&itemid=123'
          },
          {
            'type'=> 'postback',
            'label'=> 'Add to cart',
            'data'=> 'action=add&itemid=123'
          },
          {
            'type'=> 'uri',
            'label'=> 'View detail',
            'uri'=> 'http://google.com'
          }
      ]
  	}
			];
		}
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];
			}
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";

	}
}
echo "OK";

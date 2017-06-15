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
				'type'=> 'image',
  			'originalContentUrl'=> 'https://www.cleverfiles.com/howto/wp-content/uploads/2016/08/mini.jpg',
    		'previewImageUrl'=> 'https://upload.wikimedia.org/wikipedia/commons/b/b4/JPEG_example_JPG_RIP_100.jpg'
			];
		}
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			if ($text=='ค้นหา') {
				$messages = [
					//'type' => 'text',
					//'text' => 'กรุณาระบุประเภทสินค้า'
					$columns = array();
					$img_url = "https://cdn.shopify.com/s/files/1/0379/7669/products/sampleset2_1024x1024.JPG?v=1458740363";
					for($i=0;$i<5;$i++) {
						$actions = array(
							new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("Add to Card","action=carousel&button=".$i),
							new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("View","http://www.google.com")
						);
					$column = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Title", "description", $img_url , $actions);
					$columns[] = $column;
					}
					$carousel = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder($columns);
					$outputText = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("Carousel Demo", $carousel);

			];
			}
			elseif ($text == 'เช็ค') {
				$messages = [
					'type' => 'text',
					'text' => 'กรุณาระบุรหัสสินค้า'
				];
			}

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

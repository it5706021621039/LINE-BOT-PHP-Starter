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
					'type' => 'text',
					'text' => 'กรุณาระบุประเภทสินค้า'
			];
			}
			elseif ($text == 'เช็ค') {
				$messages = [
					'type' => 'text',
					'text' => 'กรุณาระบุรหัสสินค้า'
				];
			}
			elseif ($text == 'M011-HR2810' || $text == 'M011'||$text == 'm011-hr2810'||$text == 'm011') {
				$messages = [
					'type'=> 'image',
	  			'originalContentUrl'=> 'https://www.ktw.co.th/content/images/thumbs/w_1_0369532_hr-2810-%E0%B8%AA%E0%B9%88%E0%B8%99%E0%B9%82%E0%B8%A3%E0%B8%A3%E0%B8%B5%E0%B9%88.jpeg',
	    		'previewImageUrl'=> 'https://www.ktw.co.th/content/images/thumbs/w_1_0369532_hr-2810-%E0%B8%AA%E0%B9%88%E0%B8%99%E0%B9%82%E0%B8%A3%E0%B8%A3%E0%B8%B5%E0%B9%88.jpeg'
				];
			}
			elseif ($text=='9500nb'|| $text=='9500NB'||$text == 'M011-9500NB'||$text == 'm011-9500nb') {
				$messages=[
					'type'=>'text',
					'text'=>'เจียร์ไฟฟ้า 4" รุ่นใหม่ ฉนวน2ชั้น
แบรนด์ MAKITA
สต็อก
บางบอน : 1,695
เยาวราช : 292 อื่นๆ : 15
ราคา
List Price : 5,190
Dealer Promotion : 2,671.20
ราคาปลีกแนะนำ : 3,290
สั่งซื้อ https://www.ktw.co.th/9500nb-%E0%B9%80%E0%B8%88%E0%B8%B5%E0%B8%A2%E0%B8%A3%E0%B9%8C%E0%B9%84%E0%B8%9F%E0%B8%9F%E0%B9%89%E0%B8%B24%E0%B8%A3%E0%B8%B8%E0%B9%88%E0%B8%99%E0%B9%83%E0%B8%AB%E0%B8%A1%E0%B9%88-%E0%B8%89%E0%B8%99%E0%B8%A7%E0%B8%992%E0%B8%8A%E0%B8%B1%E0%B9%89%E0%B8%99'
				];
				$messages2=[
					'type'=> 'image',
	  			'originalContentUrl'=> 'https://www.ktw.co.th/content/images/thumbs/w_1_0363963_M011-9500NB_M011-9500NBLIM01.jpeg',
	    		'previewImageUrl'=> 'https://www.ktw.co.th/content/images/thumbs/w_1_0363963_M011-9500NB_M011-9500NBLIM01.jpeg'
				];
			}

			else {
				$messages = [
					'type' => 'text',
					'text' => 'กรุณาตรวจสอบคำสั่ง'
			];
			}


			}
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			if($messages2!="")
			{
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages,$messages2],
			];
			}else {
				$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages],
				];
			}
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
echo "Can";

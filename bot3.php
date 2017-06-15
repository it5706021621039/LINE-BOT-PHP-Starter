<?php

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('kCz7tuLk8Ox8ptZvYvLn0tB5dIZKkfQwFceq5SVNVlst6gkBC5R6N8pnxEcyp4oZASv1VeAMXO9d7zgx7FHV53qViU7G/4V1lRdTbFvg7aDxHNTFLDZYKBXO2STB6FhrYU07LyObdn3rQ14qbxe1kAdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '13f5715bad77b642878873bef93b3247']);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');
$response = $bot->replyMessage('<replyToken>', $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

{
    "type": "text",
    "text": "Hello, world 􀂲"
}

echo "eiei";

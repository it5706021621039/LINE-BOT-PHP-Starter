<?php
$ composer require linecorp/line-bot-sdk
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('kCz7tuLk8Ox8ptZvYvLn0tB5dIZKkfQwFceq5SVNVlst6gkBC5R6N8pnxEcyp4oZASv1VeAMXO9d7zgx7FHV53qViU7G/4V1lRdTbFvg7aDxHNTFLDZYKBXO2STB6FhrYU07LyObdn3rQ14qbxe1kAdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '13f5715bad77b642878873bef93b3247']);
$response = $bot->replyText('<reply token>', 'hello!');
?>

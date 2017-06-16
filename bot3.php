<?php

namespace LINE\Tests\LINEBot;
use LINE\LINEBot;
use LINE\LINEBot\Constant\ActionType;
use LINE\LINEBot\Constant\MessageType;
use LINE\LINEBot\Constant\TemplateType;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\Tests\LINEBot\Util\DummyHttpClient;
class SendTemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testReplyTemplate()
    {
        $mock = function ($testRunner, $httpMethod, $url, $data) {
            /** @var \PHPUnit_Framework_TestCase $testRunner */
            $testRunner->assertEquals('POST', $httpMethod);
            $testRunner->assertEquals('https://api.line.me/v2/bot/message/reply', $url);
            $testRunner->assertEquals('REPLY-TOKEN', $data['replyToken']);
            $testRunner->assertEquals(1, count($data['messages']));
            $message = $data['messages'][0];
            $testRunner->assertEquals(MessageType::TEMPLATE, $message['type']);
            $testRunner->assertEquals('alt test', $message['altText']);
            $template = $message['template'];
            $testRunner->assertEquals(TemplateType::BUTTONS, $template['type']);
            $testRunner->assertEquals('button title', $template['title']);
            $testRunner->assertEquals('button button', $template['text']);
            $testRunner->assertEquals('https://example.com/thumbnail.jpg', $template['thumbnailImageUrl']);
            $actions = $template['actions'];
            $testRunner->assertEquals(3, count($actions));
            $testRunner->assertEquals(ActionType::POSTBACK, $actions[0]['type']);
            $testRunner->assertEquals('postback label', $actions[0]['label']);
            $testRunner->assertEquals('post=back', $actions[0]['data']);
            $testRunner->assertEquals(ActionType::MESSAGE, $actions[1]['type']);
            $testRunner->assertEquals('message label', $actions[1]['label']);
            $testRunner->assertEquals('test message', $actions[1]['text']);
            $testRunner->assertEquals(ActionType::URI, $actions[2]['type']);
            $testRunner->assertEquals('uri label', $actions[2]['label']);
            $testRunner->assertEquals('https://example.com', $actions[2]['uri']);
            return ['status' => 200];
        };
        $bot = new LINEBot(new DummyHttpClient($this, $mock), ['channelSecret' => '13f5715bad77b642878873bef93b3247']);
        $res = $bot->replyMessage(
            'REPLY-TOKEN',
            new LINEBot\MessageBuilder\TemplateMessageBuilder(
                'alt test',
                new ButtonTemplateBuilder(
                    'button title',
                    'button button',
                    'https://example.com/thumbnail.jpg',
                    [
                        new PostbackTemplateActionBuilder('postback label', 'post=back'),
                        new MessageTemplateActionBuilder('message label', 'test message'),
                        new UriTemplateActionBuilder('uri label', 'https://example.com'),
                    ]
                )
            )
        );
        $this->assertEquals(200, $res->getHTTPStatus());
        $this->assertTrue($res->isSucceeded());
        $this->assertEquals(200, $res->getJSONDecodedBody()['status']);
    }
    public function testPushTemplate()
    {
        $mock = function ($testRunner, $httpMethod, $url, $data) {
            /** @var \PHPUnit_Framework_TestCase $testRunner */
            $testRunner->assertEquals('POST', $httpMethod);
            $testRunner->assertEquals('https://api.line.me/v2/bot/message/push', $url);
            $testRunner->assertEquals('DESTINATION', $data['to']);
            $testRunner->assertEquals(1, count($data['messages']));
            $message = $data['messages'][0];
            $testRunner->assertEquals(MessageType::TEMPLATE, $message['type']);
            $testRunner->assertEquals('alt test', $message['altText']);
            $template = $message['template'];
            $testRunner->assertEquals(TemplateType::BUTTONS, $template['type']);
            $testRunner->assertEquals('button title', $template['title']);
            $testRunner->assertEquals('button button', $template['text']);
            $testRunner->assertEquals('https://example.com/thumbnail.jpg', $template['thumbnailImageUrl']);
            $actions = $template['actions'];
            $testRunner->assertEquals(4, count($actions));
            $testRunner->assertEquals(ActionType::POSTBACK, $actions[0]['type']);
            $testRunner->assertEquals('postback label', $actions[0]['label']);
            $testRunner->assertEquals('post=back', $actions[0]['data']);
            $testRunner->assertEquals(ActionType::POSTBACK, $actions[1]['type']);
            $testRunner->assertEquals('postback label2', $actions[1]['label']);
            $testRunner->assertEquals('post=back2', $actions[1]['data']);
            $testRunner->assertEquals(ActionType::MESSAGE, $actions[2]['type']);
            $testRunner->assertEquals('message label', $actions[2]['label']);
            $testRunner->assertEquals('test message', $actions[2]['text']);
            $testRunner->assertEquals(ActionType::URI, $actions[3]['type']);
            $testRunner->assertEquals('uri label', $actions[3]['label']);
            $testRunner->assertEquals('https://example.com', $actions[3]['uri']);
            return ['status' => 200];
        };
        $bot = new LINEBot(new DummyHttpClient($this, $mock), ['channelSecret' => '13f5715bad77b642878873bef93b3247']);
        $res = $bot->pushMessage(
            'DESTINATION',
            new LINEBot\MessageBuilder\TemplateMessageBuilder(
                'alt test',
                new ButtonTemplateBuilder(
                    'button title',
                    'button button',
                    'https://example.com/thumbnail.jpg',
                    [
                        new PostbackTemplateActionBuilder('postback label', 'post=back'),
                        new PostbackTemplateActionBuilder('postback label2', 'post=back2', 'extend text'),
                        new MessageTemplateActionBuilder('message label', 'test message'),
                        new UriTemplateActionBuilder('uri label', 'https://example.com'),
                    ]
                )
            )
        );
        $this->assertEquals(200, $res->getHTTPStatus());
        $this->assertTrue($res->isSucceeded());
        $this->assertEquals(200, $res->getJSONDecodedBody()['status']);
    }
}

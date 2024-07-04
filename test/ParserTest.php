<?php

use Leuffen\MailBodyParse\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParseEmailHeader()
    {
        $mail = file_get_contents(__DIR__ . '/fixtures/email-reply-de.txt');
        $parser = new Parser();

        $email = $parser->parse($mail);

        $this->assertInstanceOf(\Leuffen\MailBodyParse\MultipartEmail::class, $email);

        $this->assertCount(1, $email->headers->from);
        $this->assertEquals('Joan Doe', $email->headers->from[0]['display']);
        $this->assertEquals('joan@example.com', $email->headers->from[0]['address']);

        $this->assertCount(1, $email->headers->to);
        $this->assertEquals('Joe Doe', $email->headers->to[0]['display']);
        $this->assertEquals('joe@example.com', $email->headers->to[0]['address']);

        $this->assertCount(0, $email->headers->cc);
        $this->assertCount(0, $email->headers->bcc);

        $this->assertEquals('Re: Auftrag Projekt', $email->headers->subject);
        $this->assertEquals('2024-07-02 12:06:54', $email->headers->date->format('Y-m-d H:i:s'));
    }

    public function testParseEmailBodyText()
    {
        $mail = file_get_contents(__DIR__ . '/fixtures/email-reply-de.txt');
        $parser = new Parser();

        $email = $parser->parse($mail);

        $this->assertStringContainsString("hi", $email->body->plainText);
    }

    public function testParseEmailBodyHtml()
    {
        $mail = file_get_contents(__DIR__ . '/fixtures/email-reply-de.txt');
        $parser = new Parser();

        $email = $parser->parse($mail);

        $this->assertEquals('', $email->body->htmlText);
    }
}

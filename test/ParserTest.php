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

        $this->assertCount(1, $email->header->from);
        $this->assertEquals('Joan Doe', $email->header->from[0]['display']);
        $this->assertEquals('joan@example.com', $email->header->from[0]['address']);

        $this->assertCount(1, $email->header->to);
        $this->assertEquals('Joe Doe', $email->header->to[0]['display']);
        $this->assertEquals('joe@example.com', $email->header->to[0]['address']);

        $this->assertCount(0, $email->header->cc);
        $this->assertCount(0, $email->header->bcc);

        $this->assertEquals('Re: Auftrag Projekt', $email->header->subject);
        $this->assertEquals('2024-07-02 12:06:54', $email->header->date->format('Y-m-d H:i:s'));
    }

    public function testParseEmailBodyText()
    {
        $mail = file_get_contents(__DIR__ . '/fixtures/email-reply-de.txt');
        $parser = new Parser();

        $email = $parser->parse($mail);

        $expected = <<<EOT
        hi
        
        > Am 27.06.2024 um 13:37 schrieb Joe Doe <joe@example.com>:
        > 
        > Viele Grüße,
        > 
        >   Joan
        > 
        
        EOT;

        $this->assertEquals($expected, $email->body->plainText);
    }

    public function testParseEmailBodyHtml()
    {
        $mail = file_get_contents(__DIR__ . '/fixtures/email-reply-de.txt');
        $parser = new Parser();

        $email = $parser->parse($mail);

        $this->assertEquals('', $email->body->htmlText);
    }
}

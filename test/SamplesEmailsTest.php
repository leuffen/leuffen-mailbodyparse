<?php

use Leuffen\MailBodyParse\Parser;

class SamplesEmailsTest extends \PHPUnit\Framework\TestCase
{
    public function testSampleEmails()
    {
        $inputs = glob(__DIR__ . '/fixtures/*.input.txt');
        $expected = glob(__DIR__ . '/fixtures/*.expected.txt');

        $parser = new Parser();

        foreach ($inputs as $index => $input) {
            $rawEmail = file_get_contents($input);
            $expectedText = file_get_contents($expected[$index]);

            $email = $parser->parse($rawEmail);
            $bodyText =  $email->body->getMessage();

            $this->assertEquals($expectedText, $bodyText);
        }
    }
}

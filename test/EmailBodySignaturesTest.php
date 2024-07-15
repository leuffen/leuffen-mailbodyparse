<?php

use Leuffen\MailBodyParse\EmailBody;

class EmailBodySignaturesTest extends \PHPUnit\Framework\TestCase
{
    private $signaturesToTest = [
        'Von meinem iPhone gesendet',
        'Gesendet von Samsung Mobile',
        'Gesendet von Mail für Windows',
    ];

    public function testSignatures()
    {
        $plainText = "test";
        $htmlText = "";

        foreach ($this->signaturesToTest as $signature) {
            $text = $plainText . "\n" . $signature;
            $emailBody = new EmailBody($text, $htmlText);

            $this->assertEquals($signature, $emailBody->getSignature());
        }
    }
}

<?php

use Leuffen\MailBodyParse\EmailBody;

class EmailBodyTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructor()
    {
        $plainText = "This is the plain text version of the email body.";
        $htmlText = "<p>This is the HTML version of the email body.</p>";

        $emailBody = new EmailBody($plainText, $htmlText);

        $this->assertEquals($plainText, $emailBody->plainText);
        $this->assertEquals($htmlText, $emailBody->htmlText);
    }
}

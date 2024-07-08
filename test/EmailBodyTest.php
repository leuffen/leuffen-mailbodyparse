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

    public function testGetMessageFromPlainTextEmail()
    {
        $plainText = "This is the plain text version of the email body.";
        $htmlText = "";

        $emailBody = new EmailBody($plainText, $htmlText);

        $this->assertEquals($plainText, $emailBody->getMessage());
    }

    public function testGetMessageAsTextFromHtmlEmail()
    {
        $plainText = "plain text";
        $htmlText = <<<EOT
        <div>
            <p>This is the HTML version of the <i>email</i> body.</p>
            <p>It contains multiple<br>paragraphs.</p>
            <ul>
                <li>Item 1</li>
                <li>Item 2</li>
            </ul>
        </div>
        EOT;

        $emailBody = new EmailBody($plainText, $htmlText);

        $expected = <<<EOT
        This is the HTML version of the email body.

        It contains multiple
        paragraphs.
        
        - Item 1
        - Item 2
        EOT;

        $this->assertEquals($expected, $emailBody->getMessage());
    }

    public function testGetMessageAsMarkdownFromHtmlEmail()
    {
        $plainText = "plain text";
        $htmlText = <<<EOT
        <div>
            <p>This is the HTML version of the <i>email</i> body.</p>
            <p>It contains multiple<br>paragraphs.</p>
            <ul>
                <li>Item 1</li>
                <li>Item 2</li>
            </ul>
        </div>
        EOT;

        $emailBody = new EmailBody($plainText, $htmlText);

        $expected = <<<EOT
        This is the HTML version of the *email* body.

        It contains multiple
        paragraphs.
        
        - Item 1
        - Item 2
        EOT;

        $this->assertEquals($expected, $emailBody->getMessageAsMarkdown());
    }
}

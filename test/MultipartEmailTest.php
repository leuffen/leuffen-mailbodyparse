<?php

use Leuffen\MailBodyParse\EmailBody;
use Leuffen\MailBodyParse\EmailHeader;
use Leuffen\MailBodyParse\MultipartEmail;
use PHPUnit\Framework\TestCase;

class MultipartEmailTest extends TestCase
{

    public function testConstructor()
    {
        $headers = $this->createStub(EmailHeader::class);
        $body = $this->createStub(EmailBody::class);
        $attachments = [];

        $multipartEmail = new MultipartEmail($headers, $body, $attachments);

        $this->assertSame($headers, $multipartEmail->header);
        $this->assertSame($body, $multipartEmail->body);
        $this->assertSame($attachments, $multipartEmail->attachments);
    }
}

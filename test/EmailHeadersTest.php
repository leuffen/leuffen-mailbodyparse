<?php

use Leuffen\MailBodyParse\EmailHeader;

class EmailHeadersTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructor()
    {
        $headers = [
            'from' => [
                ['display' => 'John Doe', 'address' => 'john@example.com', 'is_group' => false]
            ],
            'to' => [
                ['display' => 'Jane Smith', 'address' => 'jane@example.com', 'is_group' => false]
            ],
            'cc' => [
                ['display' => 'Bob Johnson', 'address' => 'bob@example.com', 'is_group' => false]
            ],
            'bcc' => [
                ['display' => 'Alice Brown', 'address' => 'alice@example.com', 'is_group' => false]
            ],
            'subject' => 'Test Email',
            'date' => '2022-01-01 10:00:00'
        ];

        $emailHeaders = new EmailHeader($headers);

        $this->assertEquals($headers['from'], $emailHeaders->from);
        $this->assertEquals($headers['to'], $emailHeaders->to);
        $this->assertEquals($headers['cc'], $emailHeaders->cc);
        $this->assertEquals($headers['bcc'], $emailHeaders->bcc);
        $this->assertEquals($headers['subject'], $emailHeaders->subject);
        $this->assertEquals((new \DateTime($headers['date']))->getTimestamp(), $emailHeaders->date->getTimestamp());
    }

    public function testEmptyParams()
    {
        $headers = [];

        $emailHeaders = new EmailHeader($headers);

        $this->assertEquals([], $emailHeaders->from);
        $this->assertEquals([], $emailHeaders->to);
        $this->assertEquals([], $emailHeaders->cc);
        $this->assertEquals([], $emailHeaders->bcc);
        $this->assertNull($emailHeaders->subject);
        $this->assertNull($emailHeaders->date);
    }

    function testFalseParams()
    {
        $headers = [
            'subject' => false,
            'date' => false
        ];

        $emailHeaders = new EmailHeader($headers);

        $this->assertNull($emailHeaders->subject);
        $this->assertNull($emailHeaders->date);
    }
}

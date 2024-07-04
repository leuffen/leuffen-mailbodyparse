<?php

namespace Leuffen\MailBodyParse;

use PhpMimeMailParser\Parser as MimeMailParser;

class Parser
{
    private MimeMailParser $parser;

    public function __construct()
    {
        $this->parser = new MimeMailParser();
    }

    public function parse(string $rawEmail): MultipartEmail
    {
        $this->parser->setText($rawEmail);

        $headers = $this->getHeaders();
        $body = $this->getBody();
        $attachments = $this->parser->getAttachments();

        return new MultipartEmail($headers, $body, $attachments);
    }

    private function getHeaders(): EmailHeader
    {
        return new EmailHeader([
            'from' => $this->parser->getAddresses('from'),
            'to' => $this->parser->getAddresses('to'),
            'cc' => $this->parser->getAddresses('cc'),
            'bcc' => $this->parser->getAddresses('bcc'),
            'subject' => $this->parser->getHeader('subject'),
            'date' => $this->parser->getHeader('date')
        ]);
    }

    private function getBody(): EmailBody
    {
        return new EmailBody(
            $this->parser->getMessageBody('text'),
            $this->parser->getMessageBody('html')
        );
    }
}

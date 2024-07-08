<?php

namespace Leuffen\MailBodyParse;

use PhpMimeMailParser\Parser as MimeMailParser;

class Parser
{
    /**
     * The MIME mail parser.
     * @var MimeMailParser
     */
    private MimeMailParser $parser;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->parser = new MimeMailParser();
    }

    /**
     * Parse the raw email into a MultipartEmail object.
     * 
     * @param string $rawEmail
     * @return MultipartEmail
     */
    public function parse(string $rawEmail): MultipartEmail
    {
        $this->parser->setText($rawEmail);

        $headers = $this->getHeaders();
        $body = $this->getBody();
        $attachments = $this->parser->getAttachments();

        return new MultipartEmail($headers, $body, $attachments);
    }

    /**
     * Get the email headers.
     * 
     * @return EmailHeader
     */
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

    /**
     * Get the email body text and html.
     * 
     * @return EmailBody
     */
    private function getBody(): EmailBody
    {
        return new EmailBody(
            $this->parser->getMessageBody('text'),
            $this->parser->getMessageBody('html')
        );
    }
}

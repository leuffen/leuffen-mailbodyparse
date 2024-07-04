<?php

namespace Leuffen\MailBodyParse;

class MultipartEmail
{
    /**
     * The email headers.
     * @var EmailHeader
     */
    public readonly EmailHeader $headers;

    /**
     * The email body.
     * @var EmailBody
     */
    public readonly EmailBody $body;

    /** 
     * Array of email attachments.
     * @var array<\PhpMimeMailParser\Attachment> 
     */
    public readonly array $attachments;

    /**
     * @param EmailHeader $header 
     * @param EmailBody $body 
     * @param array<\PhpMimeMailParser\Attachment> $attachments
     * @return void 
     */
    public function __construct(EmailHeader $header, EmailBody $body, array $attachments = [])
    {
        $this->headers = $header;
        $this->body = $body;
        $this->attachments = $attachments;
    }
}

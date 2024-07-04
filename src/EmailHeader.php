<?php

namespace Leuffen\MailBodyParse;

class EmailHeader
{
    /** 
     * Array of email addresses in the 'from' field.
     * @var array<int, array{display: string, address: string, is_group: bool}> 
     */
    public readonly array $from;

    /** 
     * Array of email addresses in the 'to' field.
     * @var array<int, array{display: string, address: string, is_group: bool}> 
     */
    public readonly array $to;

    /**
     * Array of email addresses in the 'cc' field. 
     * @var array<int, array{display: string, address: string, is_group: bool}> 
     */
    public readonly array $cc;

    /**
     * Array of email addresses in the 'bcc' field. 
     * @var array<int, array{display: string, address: string, is_group: bool}> 
     */
    public readonly array $bcc;

    /**
     * The subject of the email.
     * @var string | null
     */
    public readonly string | null $subject;


    /**
     * The date the email was sent.
     * @var \DateTime | null
     */
    public readonly \DateTime | null $date;

    /**
     * @param array{
     *    from: array<int, array{display: string, address: string, is_group: bool}>,
     *    to: array<int, array{display: string, address: string, is_group: bool}>,
     *    cc?: array<int, array{display: string, address: string, is_group: bool}>,
     *    bcc?: array<int, array{display: string, address: string, is_group: bool}>,
     *    subject?: string,
     *    date?: string
     * } $headers 
     * @return void 
     */
    public function __construct(array $headers)
    {
        $this->from = $headers['from'] ?? [];
        $this->to = $headers['to'] ?? [];
        $this->cc = $headers['cc'] ?? [];
        $this->bcc = $headers['bcc'] ?? [];
        $this->subject = !empty($headers['subject']) ? $headers['subject'] : null;
        $this->date = !empty($headers['date']) ? new \DateTime($headers['date']) : null;
    }
}

<?php

namespace Leuffen\MailBodyParse;

use EmailReplyParser\Email;
use League\HTMLToMarkdown\HtmlConverter;

class EmailBody
{
    /**
     * The plain text version of the email body.
     * @var string
     */
    public readonly string $plainText;

    /**
     * The HTML version of the email body.
     * @var string
     */
    public readonly string $htmlText;

    /**
     * The HTML to Markdown converter.
     * @var HtmlConverter
     */
    private HtmlConverter $htmlToMarkdownConverter;

    /**
     * The options to use when converting HTML to Markdown.
     * @var array
     */
    private array $htmlToMarkdownOptions = [
        'strip_tags' => true, // strip tags but keep content
        'strip_placeholder_links' => true,
        'remove_nodes' => '', // comma separated list of tags to remove (including their content)
        'hard_break' => true,
    ];

    /**
     * The parsed email.
     * @var Email|null
     */
    private Email|null $replyParsedEmail = null;

    /**
     * @param string $plainText
     * @param string $htmlText
     * @return void
     */
    public function __construct(string $plainText, string $htmlText)
    {
        $this->plainText = $plainText;
        $this->htmlText = $htmlText;
        $this->replyParsedEmail = $this->getReplyParsedEmail();
        $this->htmlToMarkdownConverter = new HtmlConverter($this->htmlToMarkdownOptions);
    }

    /**
     * Get the email body message (without reply part) as text.
     * 
     * @return string
     */
    public function getMessage(): string
    {
        $message = $this->replyParsedEmail->getVisibleText();

        // keep line breaks and lists in plaintext
        $message = strip_tags($message, ['p', 'br', 'ul', 'ol', 'li']);
        $message = $this->htmlToMarkdownConverter->convert($message);

        return $message;
    }

    /**
     * Get the email body message (without reply part) as markdown.
     * 
     * @return string
     */
    public function getMessageAsMarkdown(): string
    {
        $message = $this->replyParsedEmail->getVisibleText();
        $message = $this->htmlToMarkdownConverter->convert($message);

        return $message;
    }

    /**
     * Get the email body signature.
     * 
     * @return string 
     */
    public function getSignature(): string
    {
        $fragments = $this->replyParsedEmail->getFragments();
        $signature = "";

        foreach ($fragments as $fragment) {
            if ($fragment->isSignature()) {
                $signature .= $fragment->getContent();
            }
        }

        return $signature;
    }

    /**
     * Get the email body quote.
     * 
     * @return string 
     */
    public function getQuote(): string
    {
        $fragments = $this->replyParsedEmail->getFragments();
        $quote = "";

        foreach ($fragments as $fragment) {
            if ($fragment->isQuoted()) {
                $quote .= $fragment->getContent();
            }
        }

        return $quote;
    }

    private function getReplyParsedEmail(): Email
    {
        if (!empty($this->htmlText)) {
            $message = $this->htmlText;
        } else {
            $message = $this->plainText;
        }

        return \EmailReplyParser\EmailReplyParser::read($message);
    }
}

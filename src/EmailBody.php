<?php

namespace Leuffen\MailBodyParse;

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
    private array $toMarkdownOptions = [
        'strip_tags' => true, // strip tags but keep content
        'strip_placeholder_links' => true,
        'remove_nodes' => '', // comma separated list of tags to remove (including their content)
        'hard_break' => true,
    ];

    /**
     * @param string $plainText
     * @param string $htmlText
     * @return void
     */
    public function __construct(string $plainText, string $htmlText)
    {
        $this->plainText = $plainText;
        $this->htmlText = $htmlText;
        $this->htmlToMarkdownConverter = new HtmlConverter($this->toMarkdownOptions);
    }

    /**
     * Get the email body message (without reply part) in the specified format.
     * 
     * @param string{"text", "markdown"} $as The format of the message to return. Default is "text".
     * @return string
     */
    public function getMessage($as = 'text'): string
    {
        // dont' process if there is no html text
        if (empty($this->htmlText)) {
            // TODO: add reply parser
            return $this->plainText;
        }

        $message = $this->htmlText;

        // convert the message to plain text
        // preserve line breaks, paragraphs, and lists 
        if ($as === 'text') {
            //strip all tags except line breaks and lists
            $message = strip_tags($message, ['p', 'br', 'ul', 'ol', 'li']);
            $message = $this->htmlToMarkdownConverter->convert($message);
        }

        // convert the message to markdown
        if ($as === 'markdown') {
            $message = $this->htmlToMarkdownConverter->convert($message);
        }

        // TODO: add reply parser

        return $message;
    }

    /**
     * Get the email body signature.
     * 
     * @return string 
     */
    public function getSignature(): string
    {
        // TODO: use reply parser
        return "";
    }

    /**
     * Get the email body quote.
     * 
     * @return string 
     */
    public function getQuote(): string
    {
        // TODO: use reply parser
        return "";
    }
}

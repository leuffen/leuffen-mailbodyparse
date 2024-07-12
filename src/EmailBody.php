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
        $this->htmlToMarkdownConverter = new HtmlConverter($this->htmlToMarkdownOptions);
        $this->replyParsedEmail = $this->getReplyParsedEmail();
    }

    /**
     * Get the email body message (without reply part) as text.
     * 
     * @return string
     */
    public function getMessage(): string
    {
        $message = $this->replyParsedEmail->getVisibleText();

        return $message;
    }

    /**
     * Get the email body message (without reply part) as markdown.
     * 
     * @return string
     */
    public function getMessageAsMarkdown(): string
    {
        if ($this->htmlText === "") {
            return $this->plainText;
        }

        // TODO: create test cases with real world examples
        $message = $this->replaceWeirdHtmlFromMailClients($this->htmlText);
        $message = strip_tags($message, ['p', 'br', 'ul', 'ol', 'li', 'b', 'strong', 'i', 'em', 'u', 's', 'strike', 'a']);
        $message = \EmailReplyParser\EmailReplyParser::parseReply($message);
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
        /**
         * EmailReplyParser only works with plain text.
         * So we need to convert the HTML email to plain text first.
         */

        if (!empty($this->htmlText)) {
            $message = $this->replaceWeirdHtmlFromMailClients($this->htmlText);

            // replace <mail@example.com> with html entities
            // otherwise it would be removed by strip_tags
            $message = preg_replace('/<(.+@.+\.\w{2,5})>/', '&lt;$1&gt;', $message);

            // only keep line breaks and lists
            $message = strip_tags($message, ['div', 'p', 'br', 'ul', 'ol', 'li']);

            // use markdown converter to keep line breaks and lists
            $message = $this->htmlToMarkdownConverter->convert($message);

            // revert markdown escape lines (\-) -> breaks signature detection
            $message = str_replace('\\-', "-", $message);
        } else {
            $message = $this->plainText;
        }

        return \EmailReplyParser\EmailReplyParser::read($message);
    }


    private function replaceWeirdHtmlFromMailClients(string $html): string
    {
        // replace <div><br></div>
        $html = preg_replace('/<div><br><\/div>/', '<br>', $html);

        // replace all div <br>
        $html = preg_replace('/<div>(.*?)<\/div>/', '<br>$1', $html);

        return $html;
    }
}

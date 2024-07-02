<?php

namespace Leuffen\MailBodyParse;

class Html2Text
{


    public function parse($htmlContent) : string {
        $htmlContent = quoted_printable_decode($htmlContent);

        // Replace opening <p> tags with a newline
        $plainText = preg_replace('/<p>/i', "\n", $htmlContent);
        $plainText = preg_replace('/<div>/i', "\n", $plainText);

        // Replace closing </p> tags with two newlines (one for closing, one as a spacer)
        $plainText = preg_replace('/<\/p>/i', "", $plainText);
        $plainText = preg_replace('/<\/div>/i', "", $plainText);

        // Remove Other HTML Tags
        $plainText = strip_tags($plainText);

        // Decode HTML Entities
        $plainText = html_entity_decode($plainText);

        // Handle Extra White Spaces
        // Remove leading and trailing white spaces and ensure only single blank lines between paragraphs
        $plainText = trim(preg_replace("/[\r\n]+/", "\n\n", $plainText));

        return $plainText;
    }

}

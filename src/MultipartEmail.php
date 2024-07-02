<?php

namespace Leuffen\MailBodyParse;

class MultipartEmail
{

    public readonly Headers $headers;

    public readonly HtmlPart $parts;


    public function getParsedMessage(): ParsedMessage
    {
        return $this->parts->getMailText();
    }


}

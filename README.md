# leuffen-mailbodyparse

Parse Mail Body

## Requirements

php ext mailparse

```bash
pecl install mailparse
```

## Usage

##

```php

use Leuffen\MailBodyParse\Parser;

$file = file_get_contents('test.eml');

$parser = new Parser();
$email = $parser->parse($file);

// email header
$from = $email->header->from[0];
echo $from['display'] . ' ' . $from['address'];

// $email->header->to
// $email->header->cc
// $email->header->bcc

echo $email->header->subject;
echo $email->header->date->format('D, d M Y H:i:s');

// get message
echo $email->body->getMessage();

// or as markdown
echo $email->body->getMessage('markdown');

// get quote part
echo $email->body->getQuote();

// get signature
echo $email->body->getSignature();


```

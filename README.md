# leuffen-mailbodyparse

Parse Mail Body

## Requirements

php ext mailparse

```bash
pecl install mailparse
```

## Usage

```php

use Leuffen\MailBodyParse\Parser;

// raw email string
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

// get message as plain text
echo $email->body->getMessage();

// or as markdown
echo $email->body->getMessageAsMarkdown();

// get quote part
echo $email->body->getQuote();

// get signature
echo $email->body->getSignature();


```

## Add Sample Emails

> **!!!Make sure to manually remove all personal data before adding samples files to the repository!!!**

Copy `.txt` or `.eml` file(s) to `/samples` and run `composer run clean-samples`. This removes irrelevant header and copies the files to `/test/fixtures/[filename].input.txt`.

Next create an expecation file in `/test/fixtures/[file].expected.txt` containing the message as plain text (no HTML) and without any quotes and signatures.

The test command `composer run test` runs the mail parser over all `/test/fixtures/*.input.txt` files and verifies the expected output.

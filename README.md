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
echo $email->body->getMessageAsMarkdown();

// get quote part
echo $email->body->getQuote();

// get signature
echo $email->body->getSignature();


```

## Add Sample Emails

Copy `.txt` or `.eml` file(s) to `/samples` and run `composer run clean-samples`. This removes irrelevant header and copies the file to `/test/fixtures/[filename].input.txt`.

**Make sure to manually remove all personal data before adding samples files to the repository.**.

Create an expecation file in `/test/fixtures/[file].expected.txt` containing the message as plain text (no HTML) and without any quotes and signatures.

When you run `composer run test` the test script runs the mail parser over all `/test/fixtures/*.input.txt` and verifies the expected output.

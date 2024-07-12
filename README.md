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

## Sample Emails

You can test the body parser with your own sample emails.

Copy `.txt` or `.eml` file(s) to `/samples`. Create an `/samples/[filename].spec.txt` file that contains the expected output of the body parser. Run `composer run test-samples`.

Files in the `/samples` directory are not part of the repository. This way you can test sample emails without having to wory about data privacy.

## Clean Samples Script

The script copies sample emails from `/samples` to `/test/fixtures` and removes irrelevant headers.

> **!!!Make sure to manually remove all personal data before adding samples files to the repository!!!**

Copy `.txt` or `.eml` file(s) to `/samples` and run `composer run clean-samples`.

Create an expecation file in `/test/fixtures/[file].spec.txt` containing the message as plain text (no HTML) and without any quotes and signatures.

The test command `composer run test-samples` runs the mail parser over all `/test/fixtures/*.txt` files and verifies that the parser output equals the content of the corresponding `/test/fixtures/*.spec.txt`.

## Credits

-   https://github.com/willdurand/EmailReplyParser
-   https://github.com/php-mime-mail-parser/php-mime-mail-parser

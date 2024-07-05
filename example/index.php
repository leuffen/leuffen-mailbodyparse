<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Leuffen\MailBodyParse\Parser;


$mail = file_get_contents(__DIR__ . '/../test/fixtures/email-reply-de.txt');
$parser = new Parser();

$email = $parser->parse($mail);

$fromName = $email->header->from[0]['display'];
$fromAddress = $email->header->from[0]['address'];
$subject = $email->header->subject;
$date = $email->header->date->format('D, d M Y H:i:s');
$body = $email->body->getMessage();

echo <<<EOT
<pre>
From: $fromName <$fromAddress>
Subject: $subject
Date: $date

$body
</pre>
EOT;

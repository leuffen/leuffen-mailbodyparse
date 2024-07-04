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

$parser = new \Leuffen\MailBodyParse\Parser();

$mailBody = <<<EOT

Text 1

On xx.xx.xxxx Somebody wrote:

> Quoted Text
>

EOT;


$parts = $parser->parse($mailBody);

foreach ($parts as $part) {
    echo $part->getType() . "\n";
    echo $part->getContent() . "\n";
}

```

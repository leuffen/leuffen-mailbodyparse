<?php

use Leuffen\MailBodyParse\Parser;

class SamplesEmailsTest extends \PHPUnit\Framework\TestCase
{
    private function getSpecFilename(string $filename): string
    {
        $specFile = __DIR__ . '/fixtures/' . pathinfo($filename, PATHINFO_FILENAME) . '.spec.txt';

        if (!file_exists($specFile)) {
            $this->fail('Spec file not found: ' . $specFile);
        }

        return file_get_contents($specFile);
    }

    public function testPublicSampleEmails()
    {
        $inputFiles = glob(__DIR__ . '/fixtures/*.txt');
        $specFiles = glob(__DIR__ . '/fixtures/*.spec.txt');
        $inputs = array_diff($inputFiles, $specFiles);

        $parser = new Parser();

        foreach ($inputs as $index => $input) {
            $rawEmail = file_get_contents($input);
            $expectedText = $this->getSpecFilename($input);

            $email = $parser->parse($rawEmail);
            $bodyText =  $email->body->getMessage();

            $this->assertEquals($expectedText, $bodyText);
        }
    }
}

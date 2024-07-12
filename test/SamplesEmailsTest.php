<?php

use Leuffen\MailBodyParse\Parser;

class SamplesEmailsTest extends \PHPUnit\Framework\TestCase
{
    private function getSpecForFile(string $path, string $filename): string
    {
        $specFile = $path . pathinfo($filename, PATHINFO_FILENAME) . '.spec.txt';

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
            $expectedText = $this->getSpecForFile(__DIR__ . '/fixtures/', $input);

            $email = $parser->parse($rawEmail);
            $bodyText =  $email->body->getMessage();

            $this->assertEquals($expectedText, $bodyText);
        }
    }

    public function testPrivateSampleEmails()
    {
        $inputFiles = glob(__DIR__ . '/../samples/*.txt');
        $specFiles = glob(__DIR__ . '/../samples/*.spec.txt');
        $inputs = array_diff($inputFiles, $specFiles);

        $parser = new Parser();

        foreach ($inputs as $index => $input) {
            $rawEmail = file_get_contents($input);
            $expectedText = $this->getSpecForFile(__DIR__ . '/../samples/', $input);

            $email = $parser->parse($rawEmail);
            $bodyText =  $email->body->getMessage();

            $this->assertEquals($expectedText, $bodyText);
        }
    }
}

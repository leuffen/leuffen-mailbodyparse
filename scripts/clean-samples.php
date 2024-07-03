<?php

/**
 * Cleans up sample files and copies them to the test/fixtures directory.
 * Removes all headers except From, MIME-Version, Content-Type, Date, Subject and replaces the From header with a fixed value.
 * 
 * This script is not part of the package and is only used to clean up the test files.
 * 
 * !!!MANUAL REMOVE ALL SENSITIVE DATA FROM TEST FILES BEFORE COMMITTING!!!
 */


$inputDir = __DIR__ . '/../samples';
$outputDir = __DIR__ . '/../test/fixtures';
$allowedExtensions = ['eml', 'txt'];
$headersToKeep = ['From', 'To', 'MIME-Version', 'Content-Type', 'Date', 'Subject'];

$files = scandir($inputDir);

foreach ($files as $file) {
  $inputFile = $inputDir . '/' . $file;
  $outputFile = $outputDir . '/' . $file;
  $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

  if (!is_file($inputFile) || !in_array($fileExtension, $allowedExtensions) || is_file($outputFile)) {
    continue;
  }

  $content = file_get_contents($inputFile);

  $header = explode("\n", $content);
  $header = array_filter($header, function ($line) use ($headersToKeep) {
    // matches lines that start with one of the allowed headers
    return preg_match('/^(' . implode('|', $headersToKeep) . '):/i', $line);
  });

  $header = array_values($header);

  // replace email addresses
  foreach ($header as &$line) {
    if (str_starts_with($line, 'From:')) {
      $line = 'From: Joan Doe <joan@example.com>';
    }

    if (str_starts_with($line, 'To:')) {
      $line = 'To: Joe Doe <joe@example.com>';
    }
  }
  unset($line);

  // get body by finding first empty line after headers
  $body = explode("\n", $content);
  $body = array_slice($body, array_search('', $body) + 1);
  $body = implode("\n", $body);

  $cleanedContent = implode("\n", $header) . "\n\n" . $body;
  file_put_contents($outputFile, $cleanedContent);
}

<?php
/**
 * Generate the doc examples
 *
 * @author Enrico Zimuel (enrico.zimuel@elastic.co)
 */
declare(strict_types = 1);

use GitWrapper\GitWrapper;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$fileToParse = require 'examples_to_parse.php';
if (empty($fileToParse)) {
    die('There are no code example to convert, please check the file "examples_to_parse.php"');
}

printf ("Whitelist of files to be parsed:\n---\n");
foreach ($fileToParse as $f) {
    printf("%s\n", $f);
}
printf ("\n");

$source = 'https://raw.githubusercontent.com/elastic/built-docs/master/raw/en/elasticsearch/reference/master/alternatives_report.json';
$lines = json_decode(file_get_contents($source), true);

$hash = [];
$hashToParse = [];
foreach ($lines as $line) {
    if ($line['lang'] !== 'console') {
        continue;
    }
    $file = $line['source_location']['file'];
    $hash[$line['digest']] = $file;
    if (in_array($file, $fileToParse)) {
        $hashToParse[$line['digest']] = $file;
    }
}

printf ("Total number of examples    : %d\n", count($hash));
printf ("#examples from the whitelist: %d\n", count($hashToParse));

$clients = [
    'elasticsearch-php' => [
        'url' => 'https://github.com/elastic/elasticsearch-php',
        'branch' => 'master',
        'examples' => 'docs/examples'
    ],
    'elasticsearch-js' => [
        'url' => 'https://github.com/elastic/elasticsearch-js',
        'branch' => 'master',
        'examples' => 'docs/doc_examples'
    ],
    'go-elasticsearch' => [
        'url' => 'https://github.com/elastic/go-elasticsearch',
        'branch' => 'doc_examples',
        'examples' => '.doc/examples/doc'
    ],
    'elasticsearch-ruby' => [
        'url' => 'https://github.com/elastic/elasticsearch-ruby',
        'branch' => 'master',
        'examples' => 'examples/docs/asciidoc'
    ]
];

$langs = [];
$gitWrapper = new GitWrapper();
foreach ($clients as $lang => $repo) {
    printf ("\n---\nAnalyzing %s github repository\n---\n", $lang);
    $hashInRepo = [];
    $tmpDir = sprintf("/tmp/%s", $lang);
    if (file_exists($tmpDir)) {
        $git = $gitWrapper->workingCopy($tmpDir);
        $git->fetchAll();
    } else {
        $git = $gitWrapper->cloneRepository($repo['url'], $tmpDir);
    }
    $git->run('checkout', [$repo['branch']]);
    $result = $git->run(
        'ls-files',
        [$repo['examples']]
    );
    $files = explode("\n", $result);
    foreach ($files as $f) {
        if (empty($f)) {
            continue;
        }
        $fingerprint = basename($f, ".asciidoc");
        $hashInRepo[$fingerprint] = true;
    }
    $found = 0;
    $notFound = [];
    foreach ($hashToParse as $h => $f) {
        if (isset($hashInRepo[$h])) {
            $found++;
        } else {
            $notFound[$h] = $f;
        }
    }
    printf ("Number of missed examples: %d\n", count($hashToParse) - $found);
    if (!empty($notFound)) {
        printf("Missing the following doc examples:\n");
        foreach ($notFound as $h => $f) {
            printf("\tfile: %s, hash: %s\n", $f, $h);
        }
    }
    $notInParse = [];
    foreach ($hashInRepo as $h => $v) {
        if (!isset($hash[$h])) {
            printf ("Warning: the code %s is not anymore an example!\n", $h);
            continue;
        }
        if (!isset($hashToParse[$h])) {
            $notInParse[$h] = true;
        }
    }
    if (!empty($notInParse)) {
        printf ("You have %d examples in the repo extra whitelist:\n", count($notInParse));
        foreach ($notInParse as $h => $v) {
            printf("\t%s\n", $h);
        }
    }
}
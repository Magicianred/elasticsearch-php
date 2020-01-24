<?php
/**
 * Generate statistics about the doc examples for Elasticsearch
 * 
 * It can be used to answer questions like:
 * - Which examples are missing from the X client?
 * - Do X client has some outdated examples?
 *
 * @author Enrico Zimuel (enrico.zimuel@elastic.co)
 */
declare(strict_types = 1);

use GitWrapper\GitWrapper;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$alternativeReport  = 'https://raw.githubusercontent.com/elastic/built-docs/master/raw/en/elasticsearch/reference/master/alternatives_report.json';
$examplesToParse    = 'examples_to_parse.json';
$clientRepositories = 'client_repo_doc_examples.json';

$fileToParse = json_decode(file_get_contents($examplesToParse), true);
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

if (!file_exists($clientRepositories)) {
    die(sprintf("The %s file does not exist", $clientRepositories));
}
$clients = json_decode(file_get_contents($clientRepositories), true);

$langs = [];
$gitWrapper = new GitWrapper();
foreach ($clients as $lang => $repo) {
    printf ("\n---\nAnalyzing %s github repository\n---\n", $lang);
    $hashInRepo = [];
    $tmpDir = sprintf("%s/%s", sys_get_temp_dir(), $lang);
    if (file_exists($tmpDir)) {
        $git = $gitWrapper->workingCopy($tmpDir);
        $git->fetchAll();
    } else {
        $git = $gitWrapper->cloneRepository($repo['url'], $tmpDir);
    }
    $git->run('checkout', [$repo['branch']]);

    ## Get the list of .asciidoc files
    $files = getAsciiDocFiles(sprintf("%s/%s", $tmpDir, $repo['examples']));
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

/**
 * Return all the .asciidoc file starting from a path, including the subfolders
 */
function getAsciiDocFiles(string $path): array
{
    $result = glob(sprintf("%s/*.asciidoc", $path));
    foreach (glob(sprintf("%s/*", $path), GLOB_ONLYDIR) as $dir) {
        $result = array_merge($result, getAsciiDocFiles($dir));
    }
    return $result;
}
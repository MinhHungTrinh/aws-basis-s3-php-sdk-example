<?php

// Include the SDK using the Composer autoloader
require 'vendor/autoload.php';

$region = 'region';
$access_key = 'my-access-key';
$secret_key = 'my-secret-key';
$bucket='bucket-name';

/*
 * Register S3 Client
 * */
$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region' => $region,
    'credentials' => [
        'key' => $access_key,
        'secret' => $secret_key,
    ],
]);

/*
 Put data to S3 bucket
*/
try
{
    $key = 'hello_world.txt';
    echo "Creating a new object with key {$key}\n";
    $s3->putObject([
        'Bucket' => $bucket,
        'Key'    => $key,
        'Body'   => "Hello World!"
    ]);
}
catch (Aws\S3\Exception\S3Exception $e) {
    exit('Error: ' . $e->getAwsErrorMessage() . PHP_EOL);
}

/*
 GET Object
*/

try
{
    echo "Downloading that same object:\n";
    $result = $s3->getObject([
        'Bucket' => $bucket,
        'Key'    => $key
    ]);

    echo "\n---BEGIN---\n";
    echo $result['Body'];
    echo "\n----END----\n\n";
}
catch (Aws\S3\Exception\S3Exception $e) {
    exit('Error: ' . $e->getAwsErrorMessage() . PHP_EOL);
}

/*
 * Delete object
 * */

try
{
    echo 'Attempting to delete ' . $key . '...' . PHP_EOL;

    $result = $s3->deleteObject([
        'Bucket' => $bucket,
        'Key'    => $key
    ]);
}
catch (Aws\S3\Exception\S3Exception $e) {
    exit('Error: ' . $e->getAwsErrorMessage() . PHP_EOL);
}

/*
 * Checking Delete
 * */
try
{
    echo 'Checking to see if ' . $key . ' still exists...' . PHP_EOL;

    $result = $s3->getObject([
        'Bucket' => $bucket,
        'Key'    => $key
    ]);

    echo 'Error: ' . $key . ' still exists.';
}
catch (Aws\S3\Exception\S3Exception $e) {
    exit($e->getAwsErrorMessage());
}

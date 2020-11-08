<?php

// Include the SDK using the Composer autoloader
require 'vendor/autoload.php';

$region = 'region';
$access_key = 'my-access-key';  // Don't publish access_key and secret_key
$secret_key = 'my-secret-key';

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
 Create Unique Bucket
*/
$bucket = uniqid("php-sdk-sample-", true);
echo "Creating bucket named {$bucket}\n";
$s3->createBucket(['Bucket' => $bucket]);

// Wait until the bucket is created
$s3->waitUntil('BucketExists', ['Bucket' => $bucket]);
echo "Creating {$bucket} bucket is success\n";

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

echo 'Attempting to delete ' . $key . '...' . PHP_EOL;

$result = $s3->deleteObject([
    'Bucket' => $bucket,
    'Key'    => $key
]);

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

    exit('Error: ' . $key . ' still exists.');
}
catch (Aws\S3\Exception\S3Exception $e) {
    echo $e->getAwsErrorMessage();
}

/*
 Now, we want to delete the bucket. Buckets cannot be deleted unless they're empty.
 With the AWS SDK for PHP, you have a few options when deleting multiple objects:

  - Use deleteMatchingObjects method:
      http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.S3Client.html#_deleteMatchingObjects
  - Use the BatchDelete helper:
      http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.BatchDelete.html
  - Or individually delete the objects.

 We'll use the BatchDelete helper to delete the two objects we created.
*/
echo "Deleting all objects in bucket {$bucket}\n";
$batch = Aws\S3\BatchDelete::fromListObjects($s3, ['Bucket' => $bucket]);
$batch->delete();

/*
 Now that the bucket is empty, it can be deleted.
*/

echo "Deleting bucket {$bucket}\n";
$s3->deleteBucket(['Bucket' => $bucket]);

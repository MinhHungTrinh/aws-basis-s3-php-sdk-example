<?php

// Include the SDK using the Composer autoloader
require 'vendor/autoload.php';

$region = 'region';
$access_key = 'my-access-key';
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

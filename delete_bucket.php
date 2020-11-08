<?php

// Include the SDK using the Composer autoloader
require 'vendor/autoload.php';

$region = 'region';
$access_key = 'my-access-key';
$secret_key = 'my-secret-key';
$bucket = 'bucket-name';

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

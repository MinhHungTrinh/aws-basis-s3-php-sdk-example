# AWS SDK for PHP Sample Project

A simple PHP application illustrating usage of the AWS SDK for PHP.

## Requirements

A `composer.json` file declaring the dependency on the AWS SDK is provided. To
install Composer and the SDK, run:

    curl -sS https://getcomposer.org/installer | php
    php composer.phar install

## Running the S3 sample

- IAM security credentials (IAM user or IAM role-ec2)
- Create Bucket
- PUT object
- Get object
- Delete object and checking delete

```$xslt
php create_bucket.php
php practice_objects.php
php delete_bucket.php

or php sample.php
```

Practice (GET, PUT, DELETE) (https://docs.aws.amazon.com/AmazonS3/latest/dev/ObjectOperations.html)

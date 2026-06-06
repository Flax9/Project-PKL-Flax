<?php

if (!function_exists('get_s3_url')) {
    function get_s3_url($key)
    {
        // Jika sudah merupakan URL (meskipun mungkin broken/403 jika private), kita bisa parse key-nya
        // atau jika itu URL eksternal (ui-avatars), kembalikan langsung.
        if (filter_var($key, FILTER_VALIDATE_URL)) {
            // Cek apakah ini S3 URL dari bucket kita
            if (strpos($key, 's3.amazonaws.com') !== false || strpos($key, 'amazonaws.com') !== false) {
                // Ekstrak path/key dari URL S3
                $parsed = parse_url($key, PHP_URL_PATH);
                $key = ltrim($parsed, '/');
                // Jika URL S3 punya bucket name di path (s3.amazonaws.com/bucket/key)
                $bucket = env('AWS_BUCKET', 'pkl-flax-uploads');
                if (strpos($key, $bucket . '/') === 0) {
                    $key = substr($key, strlen($bucket) + 1);
                }
            } else {
                return $key; // URL lain (misal ui-avatars)
            }
        }

        if (empty($key) || $key === 'default.jpg') {
            return null;
        }

        try {
            $s3Client = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region'  => env('AWS_REGION', 'us-east-1')
            ]);

            $cmd = $s3Client->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET', 'pkl-flax-uploads'),
                'Key'    => $key
            ]);

            $request = $s3Client->createPresignedRequest($cmd, '+60 minutes');
            return (string)$request->getUri();
        } catch (\Exception $e) {
            log_message('error', 'S3 Presigned URL Error: ' . $e->getMessage());
            return null;
        }
    }
}

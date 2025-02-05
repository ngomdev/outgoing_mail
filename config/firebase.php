<?php

return [
    'type' => "service_account",
    'project_id' => "orbus-courriers",
    'private_key_id' => "a5692fa8ff39a990ea012e2160fd0f0d4dd9bf04",
    'private_key' => str_replace("\\n", "\n", env('FIREBASE_PRIVATE_KEY')),
    'client_email' => env('FIREBASE_CLIENT_EMAIL'),
    'client_id' => "111682882954260864139",
    'auth_uri' => "https://accounts.google.com/o/oauth2/auth",
    'token_uri' => "https://oauth2.googleapis.com/token",
    'auth_provider_x509_cert_url' => "https://www.googleapis.com/oauth2/v1/certs",
    'client_x509_cert_url' => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-wuufs%40orbus-courriers.iam.gserviceaccount.com",
    'universe_domain' => "googleapis.com",
];

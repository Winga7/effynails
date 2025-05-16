<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 📧 Configuration du Mailer par Défaut
    |--------------------------------------------------------------------------
    |
    | Cette option définit le mailer par défaut utilisé pour envoyer tous les emails
    | sauf si un autre mailer est explicitement spécifié lors de l'envoi.
    | Tous les mailers supplémentaires peuvent être configurés dans le tableau
    | "mailers". Des exemples de chaque type de mailer sont fournis.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | 📨 Configurations des Mailers
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez configurer tous les mailers utilisés par votre application
    | ainsi que leurs paramètres respectifs. Plusieurs exemples sont configurés
    | pour vous et vous êtes libre d'ajouter les vôtres selon vos besoins.
    |
    | Laravel prend en charge une variété de pilotes de "transport" mail qui peuvent
    | être utilisés lors de l'envoi d'un email. Vous pouvez spécifier celui que vous
    | utilisez pour vos mailers ci-dessous. Vous pouvez également ajouter des mailers
    | supplémentaires si nécessaire.
    |
    | Pilotes supportés : "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |                    "postmark", "resend", "log", "array",
    |                    "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | 📤 Adresse "From" Globale
    |--------------------------------------------------------------------------
    |
    | Vous pouvez souhaiter que tous les emails envoyés par votre application
    | proviennent de la même adresse. Ici, vous pouvez spécifier un nom et
    | une adresse qui seront utilisés globalement pour tous les emails
    | envoyés par votre application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | 📥 Adresse "To" Globale pour le Formulaire de Contact
    |--------------------------------------------------------------------------
    |
    | Cette adresse email recevra toutes les soumissions du formulaire de contact.
    | Elle est stockée dans le fichier .env pour la garder sécurisée et ne pas
    | l'exposer dans le code source.
    |
    */
    'to_address' => env('MAIL_TO_ADDRESS', 'contact@example.com'),

];

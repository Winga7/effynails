<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="preload" as="style" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" />
        <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" media="print" onload="this.media='all'">
        <noscript>
            <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap">
        </noscript>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

        <!-- Google Analytics - Chargement avec consentement par défaut désactivé -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-G9F0ZJNSLP"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            // Configuration avec consentement par défaut désactivé
            const isProduction = window.location.protocol === 'https:';
            gtag('config', 'G-G9F0ZJNSLP', {
                'cookie_flags': isProduction ? 'SameSite=None;Secure' : 'SameSite=Lax',
                'consent': 'default',
                'debug_mode': !isProduction
            });

            // Activation du mode de consentement
            gtag('consent', 'default', {
                'analytics_storage': 'denied'
            });
        </script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>

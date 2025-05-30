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

        <!-- Crédits Développeur -->
        <script>
            // 👨‍💻 Affichage des crédits développeurs
            const creditStyle = 'font-size: 12px; font-weight: bold; color: #38ba9e; background: #333; padding: 4px 8px; border-radius: 4px;';
            console.log(
                '%c Application développée par Andy Petitjean - TFE Ifosup 2025',
                creditStyle
            );
            console.log(
                "%c Pour plus d'informations, tapez showCredits() dans cette console",
                creditStyle
            );

            // 📋 Fonction détaillée des crédits accessibles via la console
            window.showCredits = () => {
                console.log(
                    '%c EffyNails',
                    'font-size: 16px; font-weight: bold; color: #38ba9e;'
                );
                console.log(
                    '%c Développeur: Andy Petitjean',
                    'font-size: 14px; color: #38ba9e;'
                );
                console.log('Email: andywinga@hotmail.be');
                console.log('LinkedIn: https://www.linkedin.com/in/andy-petitjean-688b7211b/');
                console.log('Site Web: https://winga.be');
                console.log('GitHub: https://github.com/Winga7');
                console.log(
                    "%c Développé dans le cadre du Travail de Fin d'Études à l'Ifosup",
                    'font-style: italic;'
                );
                console.log('Version: 1.0.0 - 2025');

                return "Merci d'avoir consulté les crédits!";
            };

            // 🎮 Easter egg: Code Konami pour afficher les crédits
            const secretCode = [
                'ArrowUp',
                'ArrowUp',
                'ArrowDown',
                'ArrowDown',
                'ArrowLeft',
                'ArrowRight',
                'ArrowLeft',
                'ArrowRight',
                'b',
                'a'
            ];
            let position = 0;

            document.addEventListener('keydown', e => {
                if (e.key === secretCode[position]) {
                    position++;

                    if (position === secretCode.length) {
                        position = 0;
                        window.showCredits();

                        // 🍞 Affichage d'un toast de confirmation temporaire
                        const toast = document.createElement('div');
                        toast.style = `
                            position: fixed;
                            bottom: 20px;
                            left: 50%;
                            transform: translateX(-50%);
                            background: rgba(56, 186, 158, 0.9);
                            color: white;
                            padding: 10px 20px;
                            border-radius: 4px;
                            z-index: 9999;
                            font-size: 14px;
                            transition: opacity 0.5s ease;
                        `;
                        toast.innerText = '💻 Crédits développeurs affichés dans la console';
                        document.body.appendChild(toast);

                        // Disparition progressive du toast après 3 secondes
                        setTimeout(() => {
                            toast.style.opacity = '0';
                            setTimeout(() => document.body.removeChild(toast), 500);
                        }, 3000);
                    }
                } else {
                    position = 0;
                }
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

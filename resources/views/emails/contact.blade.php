<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau message de contact</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap');
        
        body {
            font-family: 'Montserrat', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 0;
        }
        .header {
            background-color: #ffdae0;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #fff;
            padding: 25px;
            border: 1px solid #eee;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border-left: 3px solid #ffdae0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        h1 {
            color: #e91e63;
            margin: 0;
            font-size: 24px;
        }
        h2 {
            color: #e91e63;
            margin-top: 0;
            font-size: 18px;
            border-bottom: 1px dashed #ffdae0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
            border: 1px solid #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .details p {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #e91e63;
            display: inline-block;
            width: 60px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #ffc0cb;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
            border: 1px solid #ffb6c1;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nouveau message</h1>
        </div>
        <div class="content">
            <h2>Formulaire de contact - {{ config('app.name') }}</h2>
            
            <div class="details">
                <p><span class="label">Nom :</span> {{ $formData['name'] }}</p>
                <p><span class="label">Email :</span> <a href="mailto:{{ $formData['email'] }}" style="color: #e91e63; text-decoration: none;">{{ $formData['email'] }}</a></p>
                <p><span class="label">Date :</span> {{ date('d/m/Y H:i') }}</p>
            </div>
            
            <div class="message">
                <p class="label">Message :</p>
                <p>{{ $formData['message'] }}</p>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="mailto:{{ $formData['email'] }}" class="btn">Répondre</a>
            </div>
            
            <div class="footer">
                <p>Ce message a été envoyé depuis le formulaire de contact du site {{ config('app.name') }}.</p>
                <p>© {{ date('Y') }} {{ config('app.name') }} - Tous droits réservés</p>
            </div>
        </div>
    </div>
</body>
</html> 
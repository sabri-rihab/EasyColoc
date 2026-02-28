<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Syne', 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0a0a0f; color: #f0f0f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #111118; border-radius: 20px; border: 1px solid rgba(255,255,255,0.07); overflow: hidden; }
        .header { background: linear-gradient(135deg, #818cf8, #6ee7b7); padding: 40px; text-align: center; }
        .header h1 { margin: 0; color: #0a0a0f; font-size: 28px; font-weight: 800; letter-spacing: -0.02em; }
        .content { padding: 40px; line-height: 1.6; }
        .content p { margin-bottom: 24px; font-size: 16px; color: rgba(240,240,245,0.7); }
        .content strong { color: #f0f0f5; }
        .btn-container { text-align: center; margin: 40px 0; }
        .btn { background: linear-gradient(135deg, #818cf8, #6ee7b7); color: #0a0a0f ! Ses text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 700; font-size: 14px; display: inline-block; }
        .footer { padding: 24px; text-align: center; font-size: 12px; color: rgba(240,240,245,0.4); border-top: 1px solid rgba(255,255,255,0.07); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>EasyColoc</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>
                <strong>{{ $invitation->inviter->name }}</strong> vous invite à rejoindre sa colocation <strong>{{ $invitation->colocation->name }}</strong> sur EasyColoc.
            </p>
            <p>
                EasyColoc vous permet de gérer vos dépenses partagées, de suivre vos soldes et de maintenir une bonne entente financière au sein de votre logement.
            </p>
            <div class="btn-container">
                <a href="{{ url('/register') }}" class="btn">Rejoindre la colocation</a>
            </div>
            <p>
                Si vous avez déjà un compte, connectez-vous pour accepter l'invitation depuis votre tableau de bord.
            </p>
            <p style="font-size: 13px; font-style: italic;">
                Cette invitation expirera le {{ $invitation->expires_at->format('d/m/Y à H:i') }}.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} EasyColoc. Géré avec &hearts; pour une meilleure vie en communauté.
        </div>
    </div>
</body>
</html>

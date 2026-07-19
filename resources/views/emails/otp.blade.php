<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NagrikSathi OTP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; }
        .header { background: #1a3c6e; padding: 24px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 24px; }
        .header p { color: #93c5fd; margin: 4px 0 0; font-size: 14px; }
        .body { padding: 32px 24px; text-align: center; }
        .greeting { font-size: 16px; color: #374151; margin-bottom: 8px; }
        .otp-box { background: #f0f4ff; border: 2px dashed #1a3c6e; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .otp { font-size: 42px; font-weight: bold; color: #1a3c6e; letter-spacing: 12px; }
        .validity { color: #6b7280; font-size: 14px; margin-top: 8px; }
        .warning { background: #fff3cd; border-left: 4px solid #f97316; padding: 12px 16px; border-radius: 8px; text-align: left; margin: 16px 0; }
        .warning p { margin: 0; color: #92400e; font-size: 13px; }
        .footer { background: #f9fafb; padding: 16px 24px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { color: #9ca3af; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🇮🇳 NagrikSathi</h1>
            <p>Har Nagrik Ka Apna Sathi</p>
        </div>
        <div class="body">
            <p class="greeting">Namaskar! 🙏</p>
            <p style="color: #6b7280; font-size: 15px;">Aapka NagrikSathi login OTP neeche hai:</p>

            <div class="otp-box">
                <div class="otp">{{ $otp }}</div>
                <p class="validity">⏱️ Yeh OTP 10 minute mein expire ho jayega</p>
            </div>

            <div class="warning">
                <p>⚠️ <strong>Important:</strong> Isko kisi ke saath share mat karein.<br>
                NagrikSathi kabhi bhi aapka OTP phone ya message mein nahi maangega.</p>
            </div>

            <p style="color: #9ca3af; font-size: 13px;">
                Agar aapne yeh OTP request nahi kiya, toh is email ko ignore karein.
            </p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} NagrikSathi — Made with ❤️ for every Indian citizen</p>
            <p style="margin-top: 4px;">{{ $email }}</p>
        </div>
    </div>
</body>
</html>
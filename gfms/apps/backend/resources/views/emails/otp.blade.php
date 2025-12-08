<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GFMS Verification Code</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 30px;
            margin: 20px 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        .otp-box {
            background-color: #fff;
            border: 2px solid #2563eb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #2563eb;
            margin: 10px 0;
        }
        .expiry {
            color: #dc2626;
            font-weight: 600;
            margin: 20px 0;
        }
        .security-notice {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚗 GFMS</div>
            <h2>Government Fleet Management System</h2>
        </div>

        <p>Hello {{ $user->name }},</p>

        <p>You have requested to log in to your GFMS account. Please use the verification code below to complete your login:</p>

        <div class="otp-box">
            <div>Your Verification Code</div>
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <p class="expiry">⏱️ This code will expire in 5 minutes</p>

        <div class="security-notice">
            <strong>🔒 Security Notice:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Never share this code with anyone</li>
                <li>GFMS staff will never ask for your verification code</li>
                <li>If you didn't request this code, please ignore this email and contact your administrator</li>
            </ul>
        </div>

        <p>If you're having trouble logging in, please contact your system administrator.</p>

        <div class="footer">
            <p><strong>Kenya Government Fleet Management System</strong></p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

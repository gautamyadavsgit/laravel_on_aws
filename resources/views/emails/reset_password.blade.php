<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Gautam Real Estate</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }
        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #312e81;
            background: linear-gradient(135deg, #312e81 0%, #4338ca 100%);
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 36px 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .text {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .button-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn-reset {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
        }
        .fallback {
            background-color: #f1f5f9;
            border-radius: 10px;
            padding: 16px;
            margin-top: 24px;
            word-break: break-all;
            font-size: 12px;
            color: #64748b;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>Gautam Real Estate</h1>
                <p style="color: #c7d2fe; font-size: 13px; margin: 4px 0 0 0;">Institutional Fractional Real Estate Investing</p>
            </div>

            <!-- Main Content -->
            <div class="content">
                <div class="greeting">Hello, {{ $user->first_name }}!</div>
                <div class="text">
                    You are receiving this email because we received a password reset request for your account on Gautam Real Estate. Click the button below to reset your password:
                </div>

                <div class="button-wrapper">
                    <a href="{{ $resetUrl }}" class="btn-reset" target="_blank">Reset Password</a>
                </div>

                <div class="text" style="font-size: 13px; color: #64748b; margin-bottom: 0;">
                    This password reset link will expire in 60 minutes.<br>
                    If you did not request a password reset, no further action is required.
                </div>

                <div class="fallback">
                    <strong style="color: #334155; display: block; margin-bottom: 4px;">Having trouble with the button?</strong>
                    Copy and paste this URL into your web browser:<br>
                    <a href="{{ $resetUrl }}" style="color: #4f46e5;">{{ $resetUrl }}</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                &copy; {{ date('Y') }} Gautam Real Estate LLC. All rights reserved.<br>
                United States &bull; Investor Relations &bull; Confidential
            </div>
        </div>
    </div>
</body>
</html>

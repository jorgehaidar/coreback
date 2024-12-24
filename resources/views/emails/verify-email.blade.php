<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email Address</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
        }

        .email-body {
            font-size: 16px;
            color: #555555;
        }

        .email-body p {
            margin: 15px 0;
        }

        .email-button {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .email-button:hover {
            background-color: #45a049;
        }

        .email-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #999999;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>Verify Your Email Address</h1>
    </div>
    <div class="email-body">
        <p>Hi {{ $user->name }},</p>
        <p>Thank you for registering. Please click the button below to verify your email address:</p>
        <p style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="email-button">Verify Email</a>
        </p>
        <p>If you did not create an account, no further action is required.</p>
        <p>Thanks,<br>The {{ config('app.name') }} Team</p>
    </div>
    <div class="email-footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Verify Your Email Address</title>
    <style>
      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f9f9f9;
      }
      .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      }
      .header {
        border-bottom: 3px solid #28a745;
        padding-bottom: 20px;
        margin-bottom: 30px;
      }
      .header h1 {
        margin: 0;
        color: #28a745;
        font-size: 28px;
      }
      .content {
        margin-bottom: 30px;
      }
      .content p {
        margin: 15px 0;
      }
      .verification-button {
        display: inline-block;
        background-color: #28a745;
        color: white;
        padding: 15px 40px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        margin: 20px 0;
        text-align: center;
      }
      .verification-button:hover {
        background-color: #218838;
      }
      .verification-link {
        word-break: break-all;
        color: #007bff;
        margin: 20px 0;
        padding: 15px;
        background-color: #f5f5f5;
        border-radius: 5px;
        font-size: 12px;
      }
      .footer {
        border-top: 1px solid #e0e0e0;
        padding-top: 20px;
        margin-top: 30px;
        font-size: 12px;
        color: #666;
      }
      .footer p {
        margin: 5px 0;
      }
      .warning {
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 12px;
        border-radius: 5px;
        margin: 20px 0;
        font-size: 13px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Verify Your Email Address</h1>
      </div>

      <div class="content">
        <p>Dear {{ $user->username }},</p>

        <p>Thank you for registering with {{ config('company.name') }}. To complete your registration, you need to verify your email address.</p>

        <p>Please click the button below to verify your email:</p>

        <a href="{{ $verificationUrl }}" class="verification-button">Verify Email Address</a>

        <p>Or, if the button doesn't work, copy and paste this link in your browser:</p>
        <div class="verification-link">{{ $verificationUrl }}</div>

        <div class="warning">
          <strong>Security Notice:</strong> This verification link will expire in 60 minutes. If you did not create this account, please ignore this email or contact our support team.
          <p style="margin-top:8px;">Expires at: <strong>{{ isset($expiresAtIso) ? $expiresAtIso : 'in 60 minutes' }}</strong> (UTC)</p>
        </div>

        <p>Once verified, you will have full access to all features on {{ config('company.name') }}.</p>

        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
      </div>

      <div class="footer">
        <p><strong>{{ config('company.name') }}</strong></p>
        <p>Email: {{ config('company.support_email', config('mail.from.address')) }}</p>
        <p>Phone: {{ config('company.support_phone', config('app.support_phone', 'N/A')) }}</p>
        <p style="margin-top: 15px; color: #999;">This is an automated email. Please do not reply directly. For support, contact {{ config('company.support_email', config('mail.from.address')) }}</p>
      </div>
    </div>
  </body>
</html>

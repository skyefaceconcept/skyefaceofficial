<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Welcome to {{ config('app.name') }}</title>
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
        border-bottom: 3px solid #007bff;
        padding-bottom: 20px;
        margin-bottom: 30px;
      }
      .header h1 {
        margin: 0;
        color: #007bff;
        font-size: 28px;
      }
      .content {
        margin-bottom: 30px;
      }
      .content p {
        margin: 15px 0;
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
      .account-summary {
        background-color: #f0f8ff;
        border: 1px solid #007bff;
        border-radius: 5px;
        padding: 15px;
        margin: 20px 0;
      }
      .account-summary h5 {
        margin-top: 0;
        color: #007bff;
      }
      .account-summary p {
        margin: 8px 0;
        font-size: 14px;
      }
      .summary-label {
        font-weight: bold;
        color: #333;
      }
      .summary-value {
        color: #555;
        font-family: 'Courier New', monospace;
      }
      .security-warning {
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 12px;
        border-radius: 5px;
        margin: 15px 0;
        font-size: 13px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Welcome to {{ config('company.name') }}</h1>
      </div>

      <div class="content">
        <p>Dear {{ $user->username }},</p>

        <p>Thank you for registering with {{ config('company.name') }}. We are delighted to have you as part of our community.</p>

        <p>Your account has been successfully created and is ready to use. You can now access all the features and services available on our platform.</p>

        <div class="account-summary">
          <h5>Account Summary</h5>
          <p><span class="summary-label">Email:</span> <span class="summary-value">{{ $user->email }}</span></p>
          <p><span class="summary-label">Username:</span> <span class="summary-value">{{ $user->username }}</span></p>
          @if($plainPassword ?? false)
            <p><span class="summary-label">Temporary Password:</span> <span class="summary-value">{{ $plainPassword }}</span></p>
          @endif
        </div>

        @if($plainPassword ?? false)
        <div class="security-warning">
          <strong>⚠ Security Notice:</strong> Please change your password immediately after your first login. Never share your password with anyone. This temporary password should be changed as soon as possible.
        </div>
        @endif

        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

        <p>Best regards,<br>
        <strong>{{ config('company.name') }} Team</strong></p>
      </div>

      <div class="footer">
        <p><strong>{{ config('company.name') }}</strong></p>
        <p>Email: {{ config('company.support_email', config('mail.from.address')) }}</p>
        <p>Phone: {{ config('company.support_phone', config('app.support_phone', 'N/A')) }}</p>
        <p style="margin-top: 15px; color: #999;">This is an automated email. Please do not reply directly. For support, contact {{ config('app.support_email', config('mail.from.address')) }}</p>
      </div>
    </div>
  </body>
</html>

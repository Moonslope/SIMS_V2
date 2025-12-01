<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Student Account Created</title>
   <style>
      body {
         font-family: Arial, sans-serif;
         line-height: 1.6;
         color: #333;
         max-width: 600px;
         margin: 0 auto;
         padding: 20px;
      }

      .header {
         background-color: #271AD2;
         color: white;
         padding: 20px;
         text-align: center;
         border-radius: 8px 8px 0 0;
      }

      .content {
         background-color: #f9f9f9;
         padding: 30px;
         border: 1px solid #ddd;
         border-top: none;
      }

      .credentials-box {
         background-color: white;
         border: 2px solid #271AD2;
         border-radius: 8px;
         padding: 20px;
         margin: 20px 0;
      }

      .credential-item {
         margin: 10px 0;
         padding: 10px;
         background-color: #f5f5f5;
         border-radius: 4px;
      }

      .credential-label {
         font-weight: bold;
         color: #271AD2;
      }

      .credential-value {
         font-family: 'Courier New', monospace;
         font-size: 16px;
         color: #333;
      }

      .warning {
         background-color: #fff3cd;
         border-left: 4px solid #ffc107;
         padding: 15px;
         margin: 20px 0;
         border-radius: 4px;
      }

      .footer {
         text-align: center;
         margin-top: 30px;
         padding-top: 20px;
         border-top: 1px solid #ddd;
         color: #666;
         font-size: 12px;
      }

      .button {
         display: inline-block;
         padding: 12px 30px;
         background-color: #271AD2;
         color: white;
         text-decoration: none;
         border-radius: 5px;
         margin: 20px 0;
      }
   </style>
</head>

<body>
   <div class="header">
      <h1>Student Account Created</h1>
   </div>

   <div class="content">
      <p>Dear Parent/Guardian,</p>

      <p>We are pleased to inform you that a student portal account has been created for:</p>

      <div style="margin: 20px 0;">
         <strong>Student Name:</strong> {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name
         }}<br>
         <strong>LRN:</strong> {{ $student->learner_reference_number }}
      </div>

      <p>Below are the login credentials for the student portal:</p>

      <div class="credentials-box">
         <div class="credential-item">
            <div class="credential-label">Email Address:</div>
            <div class="credential-value">{{ $user->email }}</div>
         </div>
         <div class="credential-item">
            <div class="credential-label">Temporary Password:</div>
            <div class="credential-value">{{ $temporaryPassword }}</div>
         </div>
      </div>

      <div class="warning">
         <strong>⚠️ Important Security Notice:</strong>
         <ul style="margin: 10px 0;">
            <li>Please change the password immediately after first login</li>
            <li>Do not share these credentials with anyone</li>
            <li>Keep this information secure</li>
         </ul>
      </div>

      <div style="text-align: center;">
         <a href="{{ route('login') }}" class="button">Login to Student Portal</a>
      </div>

      <p>If you have any questions or need assistance, please contact the school administration.</p>

      {{-- <p>Best regards,<br>
         <strong>{{ config('app.name') }}</strong>
      </p> --}}
   </div>

   <div class="footer">
      <p>This is an automated email. Please do not reply to this message.</p>
      <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
   </div>
</body>

</html>
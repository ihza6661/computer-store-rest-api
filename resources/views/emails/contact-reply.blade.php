<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; border: 1px solid #ddd; }
        .field { margin: 15px 0; }
        .label { font-weight: bold; color: #2c3e50; }
        .reply-box { 
            background: #f5f5f5; 
            padding: 15px; 
            border-left: 4px solid #27ae60; 
            margin: 20px 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>We Have Replied to Your Inquiry</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $contact->name }},</p>
            
            <p>Thank you for your patience! Our team has reviewed your inquiry and provided a response below:</p>
            
            <div class="reply-box">
{{ $contact->admin_reply }}
            </div>
            
            <p>If you have any follow-up questions or need further assistance, please don't hesitate to contact us.</p>
            
            <p style="color: #666; font-size: 14px; margin-top: 20px;">
                <strong>Reference ID:</strong> #{{ $contact->id }}<br>
                <strong>Original Category:</strong> {{ ucfirst(str_replace('_', ' ', $contact->category)) }}
            </p>
            
            <p>Best regards,<br><strong>Database Computer Team</strong></p>
        </div>
        
        <div class="footer">
            <p>Database Computer | High-Quality Computer Hardware & Software</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

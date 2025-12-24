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
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Thank You for Contacting Database Computer</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $contact->name }},</p>
            
            <p>Thank you for submitting your inquiry to Database Computer. We have received your message and appreciate your interest in our products and services.</p>
            
            <div style="background: #f5f5f5; padding: 15px; border-left: 4px solid #3498db;">
                <p style="margin: 0;"><strong>Submission Reference ID:</strong> #{{ $contact->id }}</p>
                <p style="margin: 5px 0 0 0;"><strong>Submitted:</strong> {{ $contact->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
            
            <p style="margin-top: 20px;">Our team will review your message and get back to you as soon as possible, typically within 24-48 hours.</p>
            
            <h4>Your Inquiry Details:</h4>
            <ul style="color: #666; line-height: 1.8;">
                <li><strong>Category:</strong> {{ ucfirst(str_replace('_', ' ', $contact->category)) }}</li>
                <li><strong>Your Email:</strong> {{ $contact->email }}</li>
                @if($contact->phone)
                <li><strong>Your Phone:</strong> {{ $contact->phone }}</li>
                @endif
            </ul>
            
            <p style="margin-top: 20px;">If you have any additional questions in the meantime, feel free to email us at <strong>noreply@store.test</strong>.</p>
            
            <p>Best regards,<br><strong>Database Computer Team</strong></p>
        </div>
        
        <div class="footer">
            <p>Database Computer | High-Quality Computer Hardware & Software</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

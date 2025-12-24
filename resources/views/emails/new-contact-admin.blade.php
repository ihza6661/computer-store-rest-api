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
        .button { 
            display: inline-block; 
            padding: 10px 20px; 
            background: #3498db; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            margin-top: 20px;
        }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Contact Form Submission</h2>
        </div>
        
        <div class="content">
            <p>You have received a new contact form submission. Below are the details:</p>
            
            <div class="field">
                <span class="label">Name:</span> {{ $contact->name }}
            </div>
            
            <div class="field">
                <span class="label">Email:</span> {{ $contact->email }}
            </div>
            
            <div class="field">
                <span class="label">Phone:</span> {{ $contact->phone ?? 'Not provided' }}
            </div>
            
            <div class="field">
                <span class="label">Category:</span> <strong>{{ ucfirst(str_replace('_', ' ', $contact->category)) }}</strong>
            </div>
            
            <div class="field">
                <span class="label">Message:</span>
                <p>{{ $contact->message }}</p>
            </div>
            
            <div class="field">
                <span class="label">Submitted:</span> {{ $contact->created_at->format('F j, Y \a\t g:i A') }}
            </div>
            
            <p style="color: #666; font-size: 14px;">
                Please respond to the customer as soon as possible. Log in to your admin dashboard to reply.
            </p>
        </div>
        
        <div class="footer">
            <p>Database Computer Admin System</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

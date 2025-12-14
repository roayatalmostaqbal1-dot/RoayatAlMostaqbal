<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Reply</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #051824 0%, #162936 100%);
            color: #27e9b5;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #27e9b5;
        }
        .header p {
            font-size: 14px;
            color: #a0aec0;
            margin: 0;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            color: #333;
            margin-bottom: 24px;
        }
        .greeting strong {
            color: #051824;
        }
        .section {
            margin-bottom: 32px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #051824;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #27e9b5;
        }
        .reply-message {
            background-color: #f9fafb;
            border-left: 4px solid #27e9b5;
            padding: 20px;
            border-radius: 4px;
            font-size: 15px;
            line-height: 1.8;
            color: #333;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .original-message {
            background-color: #f0f4f8;
            border-left: 4px solid #cbd5e0;
            padding: 20px;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.7;
            color: #555;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .message-label {
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 8px;
        }
        .contact-info {
            background-color: #f9fafb;
            padding: 16px;
            border-radius: 4px;
            font-size: 14px;
            color: #555;
        }
        .contact-info p {
            margin-bottom: 8px;
        }
        .contact-info p:last-child {
            margin-bottom: 0;
        }
        .contact-info strong {
            color: #051824;
            font-weight: 600;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            font-size: 13px;
            color: #718096;
            margin-bottom: 8px;
        }
        .footer p:last-child {
            margin-bottom: 0;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 24px 0;
        }
        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }
            .header {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 24px;
            }
            .content {
                padding: 30px 20px;
            }
            .reply-message,
            .original-message,
            .contact-info {
                padding: 16px;
            }
            .footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ We've Replied</h1>
            <p>Your message has been answered</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                Hi <strong>{{ $contact->name }}</strong>,
                <br><br>
                Thank you for reaching out to us. We've reviewed your message and have prepared a response below.
            </div>

            <!-- Reply Section -->
            <div class="section">
                <div class="section-title">Our Reply</div>
                <div class="reply-message">{{ $replyMessage }}</div>
            </div>

            <div class="divider"></div>

            <!-- Original Message Reference -->
            <div class="section">
                <div class="section-title">Your Original Message</div>
                <div class="message-label">Sent on {{ $contact->created_at->format('F j, Y \a\t g:i A') }}</div>
                <div class="original-message">{{ $contact->message }}</div>
            </div>

            <div class="divider"></div>

            <!-- Contact Information -->
            <div class="section">
                <div class="section-title">Your Information</div>
                <div class="contact-info">
                    <p><strong>Name:</strong> {{ $contact->name }}</p>
                    <p><strong>Email:</strong> {{ $contact->email }}</p>
                    @if($contact->phone)
                        <p><strong>Phone:</strong> {{ $contact->phone }}</p>
                    @endif
                    @if($contact->company)
                        <p><strong>Company:</strong> {{ $contact->company }}</p>
                    @endif
                    @if($contact->service)
                        <p><strong>Service Requested:</strong> {{ $contact->service }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>If you have any further questions, please don't hesitate to contact us.</p>
            <p style="color: #cbd5e0; font-size: 12px; margin-top: 16px;">
                © {{ date('Y') }} Royat Al Mostaqbal. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>


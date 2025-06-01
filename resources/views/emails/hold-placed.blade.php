<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
        .header { background-color: #e0f2f7; padding: 15px; text-align: center; border-bottom: 1px solid #cce5ed; }
        .header h1 { margin: 0; color: #0056b3; }
        .content { padding: 20px; }
        .footer { padding: 15px; text-align: center; font-size: 0.9em; color: #777; border-top: 1px solid #eee; margin-top: 20px; }
        .button { display: inline-block; background-color: #007bff; color: #ffffff !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .important { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Important Account Update</h1>
        </div>
        <div class="content">
            <p>Hi {{ $student->first_name }},</p>
            <p>This is to inform you that a **hold has been placed on your student account** at {{ config('app.name') }}.</p>
            <p class="important">Reason for Hold: {{ $hold->reason }}</p>
            <p>
                This hold may affect your ability to register for courses, view grades, or access other student services.
                To resolve this hold, please contact the **Registrar's Office** as soon as possible.
            </p>
            <p>
                You can typically reach them by:
                <ul>
                    <li>Phone: [Registrar's Office Phone Number]</li>
                    <li>Email: [Registrar's Office Email Address]</li>
                    <li>Visiting their office during business hours: [Registrar's Office Location/Hours]</li>
                </ul>
            </p>
            <p>Please resolve this promptly to avoid any disruption to your academic progress.</p>
            <p>Thank you,</p>
            <p>The {{ config('app.name') }} Administration</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
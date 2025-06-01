<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
        .header { background-color: #d4edda; padding: 15px; text-align: center; border-bottom: 1px solid #c3e6cb; }
        .header h1 { margin: 0; color: #155724; }
        .content { padding: 20px; }
        .footer { padding: 15px; text-align: center; font-size: 0.9em; color: #777; border-top: 1px solid #eee; margin-top: 20px; }
        .button { display: inline-block; background-color: #28a745; color: #ffffff !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .success { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Hold Released!</h1>
        </div>
        <div class="content">
            <p>Hi {{ $student->first_name }},</p>
            <p>We're pleased to inform you that the **hold on your student account at {{ config('app.name') }} has been successfully released.**</p>
            <p class="success">Reason for previous hold: {{ $hold->reason }}</p>
            @if ($hold->released_at)
                <p>Released on: {{ $hold->released_at->format('F d, Y') }}</p>
            @endif
            <p>
                Your account is now clear, and you should have full access to all student services,
                including course registration, grade viewing, and financial aid information.
            </p>
            <p>
                If you have any questions or require further assistance, please don't hesitate to contact the
                Registrar's Office at [Registrar's Office Email Address] or [Registrar's Office Phone Number].
            </p>
            <p>Thank you,</p>
            <p>The {{ config('app.name') }} Administration</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
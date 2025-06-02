<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Recheck Application Submitted</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #004085; /* Dark Blue for branding */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
            color: #333333;
        }
        .content h1 {
            color: #004085;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .content h2 {
            color: #004085;
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 5px;
        }
        .details-table {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }
        .details-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .details-table td:first-child {
            font-weight: bold;
            background-color: #f9f9f9;
            width: 35%;
        }
        .footer {
            background-color: #e9ecef;
            color: #6c757d;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
        }
        .button {
            display: inline-block;
            background-color: #007bff; /* USP/Academic Blue */
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            background-color: #ffc107; /* Yellow for Pending */
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{ config('app.name') }}
        </div>
        <div class="content">
            <h1>Grade Recheck Application Submitted</h1>
            <p>Dear {{ $application->full_name }},</p>
            <p>Thank you for submitting your application for reconsideration of your course grade.</p>

            <h2 style="color: #004085;">Your Application ID: <span style="color: #d9534f;">#{{ $application->id }}</span></h2>
            <p>Please keep this ID for your reference.</p>

            <h2 style="color: #004085;">Application Details</h2>
            <table class="details-table">
                <tr>
                    <td>Course Code:</td>
                    <td>{{ $application->course_code }}</td>
                </tr>
                <tr>
                    <td>Course Title:</td>
                    <td>{{ $application->course_title }}</td>
                </tr>
                <tr>
                    <td>Submitted On:</td>
                    <td>{{ $application->created_at->format('F d, Y H:i A') }}</td>
                </tr>
                <tr>
                    <td>Current Status:</td>
                    <td><span class="badge" style="background-color: #ffc107; color: #333;">{{ ucfirst($application->status->value) }}</span></td>
                </tr>
            </table>

            <p>We will review your application and notify you once its status is updated.</p>
            <p>If you have any questions, please contact the administration.</p>

            <p style="margin-top: 30px;">Thanks,<br>{{ config('app.name') }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
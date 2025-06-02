<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification of Exam Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px 60px;
            color: #000;
            font-size: 12pt;
        }

        h1 {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .header, .footer {
            margin-bottom: 25px;
        }

        .header p {
            margin: 3px 0;
        }

        .student-info {
            margin-bottom: 30px;
        }

        .student-info p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 11pt;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .notes {
            font-size: 10pt;
            color: #333;
        }

        .notes p {
            margin: 4px 0;
        }

        .legend {
            margin-top: 15px;
        }

        .legend h3 {
            margin-bottom: 5px;
            font-size: 11pt;
        }

        .legend table {
            width: 60%;
            font-size: 10pt;
            margin-top: 5px;
        }

        .legend td {
            padding: 2px 5px;
        }

        .right {
            text-align: right;
        }
    </style>
</head>
<body>

    <h1>NOTIFICATION OF EXAM RESULTS</h1>

    <div class="header">
        <p><strong>{{ $student->first_name }} {{ $student->last_name }}</strong></p>
        <p>P O Box {{ $student->postal_box ?? '9968' }}</p>
        <p>Nadi Airport, Fiji</p>
        <br>
        <p><strong>Our Ref:</strong> {{ $student->student_id }}</p>
        <p><strong>Campus:</strong> {{ $student->campus ?? 'Laucala' }}</p>
    </div>

    <div class="student-info">
        <p><strong>Program:</strong> {{ $student->program->name ?? 'Bach of Software Engineering' }}</p>
        <p><strong>Semester:</strong> Semester 2 2024</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Grade</th>
                <th>Letter Grade</th>
                <th>Campus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($student->courses as $course)
                @if($course->pivot->status == "completed" && $course->pivot->grade !== null)
                <tr>
                    <td>{{ $course->course_code }}</td>
                    <td>{{ $course->course_title }}</td>
                    <td>{{ number_format($course->pivot->grade, 1) }}</td>
                    <td>{{ $course->pivot->letter_grade }}</td>
                    <td>{{ $course->pivot->campus ?? 'L' }}</td>
                </tr>
                @endif
                @if($course->pivot->status == "in progress")
                <tr>
                    <td>{{ $course->course_code }}</td>
                    <td>{{ $course->course_title }}</td>
                    <td colspan="3">In Progress</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="right">
        <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    <div class="notes">
        <p>1. Fee for reconsideration of course grade is FJ$100</p>
        <p>2. Issued without alterations or erasures</p>
        <p>3. Invalid unless official university stamp appears</p>
        <p>4. English is the medium of instruction at USP</p>
    </div>

    <div class="legend">
        <h3>Key to Grading System</h3>
        <table>
            <tr>
                <td><strong>A+</strong></td><td>Pass with Distinction</td>
                <td><strong>D/E</strong></td><td>Fail</td>
            </tr>
            <tr>
                <td><strong>B+</strong></td><td>Pass with Credit</td>
                <td><strong>IP</strong></td><td>In Progress</td>
            </tr>
            <tr>
                <td><strong>C</strong></td><td>Pass</td>
                <td><strong>I</strong></td><td>Incomplete</td>
            </tr>
            <tr>
                <td><strong>R</strong></td><td>Restricted Pass</td>
                <td><strong>NV/U</strong></td><td>Not Competent/Unsatisfactory</td>
            </tr>
        </table>
    </div>

</body>
</html>

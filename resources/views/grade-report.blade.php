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
        {{-- Fill in PO Box from student data --}}
        <p>{{ $student->postal_address ?? '9968' }}</p> 
        <br>
        <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
        <p><strong>Campus:</strong> {{ $student->campus ?? 'Laucala' }}</p>
    </div>

    <div class="student-info">
        <p><strong>Program:</strong> {{ $student->program->name ?? 'Bach of Software Engineering' }}</p>
        <p><strong>Semester:</strong> {{ $activeSemester->name ?? 'N/A' }}</p>
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
            {{-- Loop through completedCourseStudents as passed from the controller --}}
            @foreach($completedCourseStudents as $csRecord)
                {{-- Only display completed courses with a grade --}}
                @if($csRecord->grade !== null)
                <tr>
                    <td>{{ $csRecord->course->course_code }}</td>
                    <td>{{ $csRecord->course->course_title }}</td>
                   
                    <td>{{ number_format($csRecord->grade, 1) }}</td>
                    <td>{{ $csRecord->letter_grade }}</td>
                    <td>{{ $csRecord->campus ?? 'L' }}</td>
                </tr>
                @endif
                {{-- Removed the 'In Progress' block as this report typically focuses on graded courses --}}
            @endforeach
        </tbody>
    </table>

    <div class="right">
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($now)->format('d F Y') }}</p> {{-- Using the $now variable passed from controller, formatted for report --}}
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
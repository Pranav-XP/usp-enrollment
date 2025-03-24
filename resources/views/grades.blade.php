<x-layouts.app :title="__('Your Grades')">
    <div class="">
        <h2 class="text-2xl font-semibold text-teal-800 dark:text-teal-300 mb-4">Grades for {{ $student->first_name }} {{ $student->last_name }}</h2>

        @if($student->courses->isEmpty())
            <p>You are not enrolled in any courses.</p>
        @else
            <table class="min-w-full table-auto">
                <thead class="">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium ">Course Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium ">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalCredits = 0;
                        $totalGradePoints = 0;
                    @endphp

                    @foreach($student->courses as $course)
                        @if($course->pivot->status == "completed") <!-- Only consider completed courses -->
                            <tr class="border-t">
                                <td class="px-4 py-2 text-sm ">{{ $course->course_code }} - {{ $course->course_title }}</td>
                                <td class="px-4 py-2 text-sm ">
                                    @if($course->pivot->grade)
                                        @php
                                            $grade = $course->pivot->grade;
                                            $totalGradePoints += $grade;
                                            $totalCredits += 1; // assuming each course is 1 credit, adjust if needed
                                        @endphp
                                        {{ $grade }}
                                    @else
                                        <span class="text-gray-400">Grade not assigned</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            @if($totalCredits > 0)
                @php
                    $gpa = $totalGradePoints / $totalCredits;
                @endphp
                <div class="mt-4">
                    <strong>GPA: </strong> {{ number_format($gpa, 2) }}
                </div>
            @else
                <div class="mt-4 text-gray-400">GPA calculation unavailable (no completed courses with grades assigned).</div>
            @endif
        @endif
    </div>
</x-layouts.app>

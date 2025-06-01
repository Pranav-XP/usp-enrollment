<x-layouts.app :title="__('Your Grades')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Your Academic Grades</flux:heading>
        <flux:subheading>View your completed course grades and GPA here.</flux:subheading>

        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mt-4">Grades for {{ $student->first_name }} {{ $student->last_name }}</h2>

        @if($student->courses->isEmpty())
            <p class="text-gray-600 dark:text-gray-400 p-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md">You are not enrolled in any courses yet, or no grades have been assigned.</p>
        @else
            <div class="mt-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Numerical Grade</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Letter Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @php
                                $gradedCoursesCount = 0; // Renamed for clarity
                                $totalGradePoints = 0;
                            @endphp

                            @foreach($student->courses as $course)
                                @if($course->pivot->status == "completed") {{-- Only consider completed courses --}}
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition duration-150 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $course->course_code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $course->course_title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            @if($course->pivot->grade !== null)
                                                {{ number_format($course->pivot->grade, 1) }}
                                                @php
                                                    $totalGradePoints += $course->pivot->grade;
                                                    $gradedCoursesCount += 1; // Increment count for each graded course
                                                @endphp
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            @if($course->pivot->grade !== null)
                                                {{ $course->pivot->letter_grade }}
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($gradedCoursesCount > 0)
                @php
                    $gpa = $totalGradePoints / $gradedCoursesCount;
                @endphp
                <div class="mt-6 p-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md">
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        Overall GPA: <span class="text-teal-600 dark:text-teal-400">{{ number_format($gpa, 2) }}</span>
                    </p>
                </div>
            @else
                <div class="mt-6 p-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md text-gray-600 dark:text-gray-400">
                    GPA calculation unavailable (no completed courses with grades assigned).
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
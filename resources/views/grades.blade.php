<x-layouts.app :title="__('Your Grades')">
    <div class="max-w-4xl mx-auto p-6  rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-teal-800 mb-4">Grades for {{ $student->first_name }} {{ $student->last_name }}</h2>

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
                    @foreach($student->courses as $course)
                        <tr class="border-t">
                            <td class="px-4 py-2 text-sm ">{{ $course->course_code }} - {{ $course->course_title }}</td>
                            <td class="px-4 py-2 text-sm ">
                                @if($course->pivot->grade)
                                    {{ $course->pivot->grade }}
                                @else
                                    <span class="text-gray-400">Grade not assigned</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-layouts.app>


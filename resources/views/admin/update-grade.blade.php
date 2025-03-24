<x-layouts.app :title="__('Update Student Grades')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Update Grades for {{ $student->first_name }} {{ $student->last_name }}</flux:heading>

        <form method="POST" action="{{ route('admin.students.updateGrade', $student->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr">
                            <th class="py-2 px-4 text-sm font-medium ">Course Code</th>
                            <th class="py-2 px-4 text-sm font-medium ">Course Title</th>
                            <th class="py-2 px-4 text-sm font-medium ">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrolledCourses as $course)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $course->course_code }}</td>
                                <td class="px-4 py-2">{{ $course->course_title }}</td>
                                <td class="px-4 py-2">
                                    <select 
                                    name="grades[{{ $course->id }}]" 
                                    class="mt-1 p-2 rounded-md border w-full bg-white text-gray-700"
                                >
                                    <option value="" disabled>Select Grade</option>
                                    <option value="4.5" {{ old('grades.' . $course->id, $course->pivot->grade) == '4.5' ? 'selected' : '' }}>A+</option>
                                    <option value="4.0" {{ old('grades.' . $course->id, $course->pivot->grade) == '4.0' ? 'selected' : '' }}>A</option>
                                    <option value="3.5" {{ old('grades.' . $course->id, $course->pivot->grade) == '3.5' ? 'selected' : '' }}>B+</option>
                                    <option value="3.0" {{ old('grades.' . $course->id, $course->pivot->grade) == '3.0' ? 'selected' : '' }}>B</option>
                                    <option value="2.5" {{ old('grades.' . $course->id, $course->pivot->grade) == '2.5' ? 'selected' : '' }}>C+</option>
                                    <option value="2.0" {{ old('grades.' . $course->id, $course->pivot->grade) == '2.0' ? 'selected' : '' }}>C</option>
                                </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-md">Update Grades</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>

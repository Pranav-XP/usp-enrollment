<x-layouts.app :title="__('Update Student Grades')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Update Grades for {{ $student->first_name }} {{ $student->last_name }}</flux:heading>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.students.updateGrade', $student->id) }}" class="space-y-6"> {{-- Changed route to updateGrades --}}
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach ($enrolledCourses as $course)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $course->course_code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $course->course_title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{-- Using flux:select for visual consistency --}}
                                        <flux:select name="grades[{{ $course->id }}]" id="grade-{{ $course->id }}">
                                            <option value="" disabled>Select Grade</option>
                                            <option value="4.5" {{ old('grades.' . $course->id, $course->pivot->grade) == '4.5' ? 'selected' : '' }}>A+</option>
                                            <option value="4.0" {{ old('grades.' . $course->id, $course->pivot->grade) == '4.0' ? 'selected' : '' }}>A</option>
                                            <option value="3.5" {{ old('grades.' . $course->id, $course->pivot->grade) == '3.5' ? 'selected' : '' }}>B+</option>
                                            <option value="3.0" {{ old('grades.' . $course->id, $course->pivot->grade) == '3.0' ? 'selected' : '' }}>B</option>
                                            <option value="2.5" {{ old('grades.' . $course->id, $course->pivot->grade) == '2.5' ? 'selected' : '' }}>C+</option>
                                            <option value="2.0" {{ old('grades.' . $course->id, $course->pivot->grade) == '2.0' ? 'selected' : '' }}>C</option>
                                            <option value="1.5" {{ old('grades.' . $course->id, $course->pivot->grade) == '1.5' ? 'selected' : '' }}>R</option> {{-- Assuming 'R' corresponds to 1.5 --}}
                                            <option value="1.0" {{ old('grades.' . $course->id, $course->pivot->grade) == '1.0' ? 'selected' : '' }}>D</option>
                                            <option value="0" {{ old('grades.' . $course->id, $course->pivot->grade) == '0' ? 'selected' : '' }}>E</option>
                                        </flux:select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <flux:button variant="danger" href="{{ route('admin.students') }}"> {{-- Assuming this redirects back to student list --}}
                    Cancel
                </flux:button>
                <flux:button variant="primary" type="submit">
                    Update Grades
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
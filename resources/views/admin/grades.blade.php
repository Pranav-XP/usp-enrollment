<x-layouts.app :title="__('Manage Student Grades')"> {{-- Updated title for clarity --}}
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Manage Student Grades</flux:heading> {{-- Consistent heading --}}
        <flux:subheading>Select a student to view or update their course grades.</flux:subheading> {{-- Consistent subheading --}}

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Display the list of students in a consistent table style --}}
        @if ($students->isEmpty())
            <p class="text-gray-600 dark:text-gray-400 p-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md">No students found.</p>
        @else
            <div class="mt-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Program</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach($students as $student)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $student->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $student->program->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <flux:button variant="primary" href="{{ route('admin.students.gradeForm', $student->id) }}">
                                           Update Grades
                                        </flux:button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
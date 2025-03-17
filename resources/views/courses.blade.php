<x-layouts.app :title="__('Courses')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading>Course Enrollment</flux:heading>
        <flux:subheading>View and enroll in courses here.</flux:subheading>

        @if ($programs->isEmpty())
            <p class="text-gray-500">No programs available.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-600">Program Code</th>
                            <th class="px-6 py-3 text-left text-gray-600">Program Name</th>
                            <th class="px-6 py-3 text-left text-gray-600">Description</th> <!-- New Column -->
                            <th class="px-6 py-3 text-left text-gray-600">Duration (Years)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $program)
                            <tr class="border-b">
                                <td class="px-6 py-4">{{ $program->program_code }}</td>
                                <td class="px-6 py-4">{{ $program->name }}</td>
                                <td class="px-6 py-4">{{ $program->description }}</td> <!-- Description -->
                                <td class="px-6 py-4">{{ $program->duration }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layouts.app>

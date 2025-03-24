<x-layouts.app :title="__('Enrolment Settings')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading>Students Grades</flux:heading>
        <flux:subheading>Select student to update grades</flux:subheading>

        <!-- Display the list of students -->
       
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 px-4 text-sm font-medium ">Student Name</th>
                        <th class="py-2 px-4 text-sm font-medium ">Email</th>
                        <th class="py-2 px-4 text-sm font-medium ">Program</th>
                        <th class="py-2 px-4 text-sm font-medium ">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr class="border-b text-center">
                            <td class="py-2 px-4 text-sm">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td class="py-2 px-4 text-sm">{{ $student->email }}</td>
                            <td class="py-2 px-4 text-sm">{{ $student->program->name }}</td>
                            <td class="py-2 px-4 text-sm">
                                <flux:button variant="primary" href="{{ route('admin.students.gradeForm', $student->id) }}" 
                                   >
                                   Update Grades
                            </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</x-layouts.app>

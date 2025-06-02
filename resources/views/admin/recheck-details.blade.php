<x-layouts.app :title="__('Recheck Application Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Grade Recheck Application Details</flux:heading>
        <flux:subheading>Review and update the status of this grade reconsideration application.</flux:subheading>

        {{-- Success/Error/Info Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('info') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md space-y-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4">Application #{{ $application->id }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Student Name:</p>
                    <p class="text-base text-gray-900 dark:text-gray-200">{{ $application->student->first_name }} {{ $application->student->last_name }} ({{ $application->student->student_id }})</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Student Email:</p>
                    <p class="text-base text-gray-900 dark:text-gray-200">{{ $application->email }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Course:</p>
                    <p class="text-base text-gray-900 dark:text-gray-200">{{ $application->course->course_code }} - {{ $application->course->course_title }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lecturer Name:</p>
                    <p class="text-base text-gray-900 dark:text-gray-200">{{ $application->course_lecturer_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Receipt No:</p>
                    <p class="text-base text-gray-900 dark:text-gray-200">{{ $application->receipt_no }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Confirmation:</p>
                    @if($application->payment_confirmation_path)
                        {{-- Link to download the private file --}}
                        <a href="{{ route('admin.recheck.download', $application->id) }}" class="text-teal-600 hover:underline dark:text-teal-400">
                            Download File ({{ basename($application->payment_confirmation_path) }})
                        </a>
                    @else
                        <p class="text-base text-gray-900 dark:text-gray-200">No file uploaded</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Application Status:</p>
                    <flux:badge
                        color="{{ $application->status->value == 'pending' ? 'yellow' : ($application->status->value == 'approved' ? 'green' : 'red') }}"
                        variant="outline"
                        size="md">
                        {{ ucfirst($application->status->value) }}
                    </flux:badge>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Applied On:</p>
                    <p class="text-base text-gray-900 dark:text-gray-200">{{ $application->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">Update Application Status</h3>

            <form action="{{ route('admin.recheck.update', $application->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                <flux:field>
                    <flux:label for="status">Status</flux:label>
                    <flux:select id="status" name="status" required>
                        @foreach(\App\Enums\GradeRecheckStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ $application->status == $status ? 'selected' : '' }}>
                                {{ ucfirst($status->value) }}
                            </option>
                        @endforeach
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>

                <flux:field>
                    <flux:label for="admin_notes">Admin Notes</flux:label>
                    <flux:textarea id="admin_notes" name="admin_notes" rows="4">{{ old('admin_notes', $application->admin_notes) }}</flux:textarea>
                    <flux:error name="admin_notes" />
                </flux:field>

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">Update Status</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
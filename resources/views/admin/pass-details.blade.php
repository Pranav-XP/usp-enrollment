<x-layouts.app :title="__('Admin: Application Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Application Details: {{ Str::limit($application['id'], 8, '') }}</flux:heading>
        <flux:subheading>
            Detailed view of the special pass application.
        </flux:subheading>

        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md space-y-6">
            {{-- Section: Personal Details --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4">Personal Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                <div>
                    <p class="font-medium">Student ID Number:</p>
                    <p>{{ $application['studentIdNumber'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Full Name:</p>
                    <p>{{ $application['fullName'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Email:</p>
                    <p>{{ $application['email'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Date of Birth:</p>
                    <p>{{ \Carbon\Carbon::parse($application['dateOfBirth'])->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p class="font-medium">Campus:</p>
                    <p>{{ $application['campus'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Telephone:</p>
                    <p>{{ $application['telephone'] ?? 'N/A' }}</p>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <p class="font-medium">Postal Address:</p>
                    <p>{{ $application['postalAddress'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Semester/Trimester:</p>
                    <p>{{ $application['semesterTrimester'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Year:</p>
                    <p>{{ $application['year'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Submitted At:</p>
                    <p>{{ \Carbon\Carbon::parse($application['submittedAt'])->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>

            {{-- Section: Missed Exam Details --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">Missed Exam Details</h3>
            @if (!empty($application['missedExams']))
                @foreach ($application['missedExams'] as $index => $exam)
                    <div class="p-4 border border-gray-200 dark:border-zinc-700 rounded-md space-y-2 mb-4">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300">Course #{{ $index + 1 }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                            <div>
                                <p class="font-medium">Course Code:</p>
                                <p>{{ $exam['courseCode'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-medium">Exam Date:</p>
                                <p>{{ \Carbon\Carbon::parse($exam['examDate'])->format('Y-m-d') }}</p>
                            </div>
                            <div>
                                <p class="font-medium">Exam Start Time:</p>
                                <p>{{ $exam['examStartTime'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-medium">Applying For:</p>
                                <p>{{ $exam['applyingFor'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-600 dark:text-gray-400">No missed exam details provided.</p>
            @endif

            {{-- Section: Application Details --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 border-b pb-2 mb-4 mt-8">Application Details</h3>
            <div class="space-y-4 text-gray-700 dark:text-gray-300">
                <div>
                    <p class="font-medium">Reasons:</p>
                    <p>{{ $application['reasons'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Declaration Agreed:</p>
                    <p>{{ $application['declarationAgreed'] ? 'Yes' : 'No' }}</p>
                </div>
                <div>
                    <p class="font-medium">Supporting Documents:</p>
                    @if (!empty($application['supportingDocumentUrls']))
                        <ul class="list-disc ml-5 mt-2">
                            @foreach ($application['supportingDocumentUrls'] as $url)
                                <li><a href="{{ asset($url) }}" target="_blank" class="text-teal-600 hover:underline dark:text-teal-400">
                                    {{ basename($url) }}
                                </a></li>
                            @endforeach
                        </ul>
                    @else
                        <p>No supporting documents uploaded.</p>
                    @endif
                </div>
            </div>

            <div class="flex justify-start mt-6">
                <flux:button href="{{ route('admin.pass.index') }}" variant="primary">
                    Back to Applications List
                </flux:button>
            </div>
        </div>
    </div>
</x-layouts.app>
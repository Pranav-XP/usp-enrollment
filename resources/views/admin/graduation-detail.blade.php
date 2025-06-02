<x-layouts.app :title="__('Graduation Application Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex justify-between items-center">
            <flux:heading size="xl">Graduation Application Details</flux:heading>
            {{-- This button is styled to match the example's download button --}}
            <a href="{{ route('admin.graduation.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Back to Applications
            </a>
        </div>
        <flux:subheading>Detailed view of the student's graduation application.</flux:subheading>

        {{-- Error Message (consistent styling) --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (empty($application))
            <p class="text-gray-600 dark:text-gray-400 p-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md">Application not found or could not be loaded.</p>
        @else
            {{-- Main content block with consistent background, shadow, and rounded corners --}}
            <div class="mt-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6 space-y-6">

                {{-- Application Overview Section --}}
                <div class="border-b border-gray-200 dark:border-zinc-700 pb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Application ID: {{ $application['id'] ?? 'N/A' }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Submitted At: {{ \Carbon\Carbon::parse($application['submittedAt'])->format('d M Y H:i') ?? 'N/A' }}</p>
                </div>

                {{-- SECTION A: PERSONAL DETAILS --}}
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mt-4">SECTION A: PERSONAL DETAILS</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Student ID Number:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['studentIdNumber'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Full Name:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['name'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Email:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['email'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Date of Birth:</p>
                        <p class="font-medium dark:text-gray-200 text-gray-900">{{ \Carbon\Carbon::parse($application['dateOfBirth'])->format('d M Y') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Telephone:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['telephone'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Postal Address:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['postalAddress'] ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- SECTION B: GRADUATION PROGRAMME DETAILS --}}
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mt-4">SECTION B: GRADUATION PROGRAMME DETAILS</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Programme Type:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['programmeType'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Programme:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['programme'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Major 1:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['major1'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Major 2:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['major2'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Minor:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['minor'] ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- SECTION C: GRADUATION ATTENDANCE --}}
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mt-4">SECTION C: GRADUATION ATTENDANCE</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Ceremony Venue:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">{{ $application['graduationCeremonyVenue'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm dark:text-gray-400">Will Attend Ceremony:</p>
                        <p class="font-medium text-gray-900 dark:text-gray-200">
                            @if (isset($application['willAttendGraduation']))
                                {{ $application['willAttendGraduation'] ? 'Yes' : 'No' }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                {{-- SECTION D: DECLARATION --}}
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mt-4">SECTION D: DECLARATION</h2>
                <div>
                    <p class="text-gray-500 text-sm dark:text-gray-400">Declaration Agreed:</p>
                    <p class="font-medium text-gray-900 dark:text-gray-200">
                        @if (isset($application['declarationAgreed']))
                            {{ $application['declarationAgreed'] ? 'Agreed' : 'Not Agreed' }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
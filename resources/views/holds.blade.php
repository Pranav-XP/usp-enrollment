{{-- resources/views/student/holds/index.blade.php --}}
<x-layouts.app :title="__('My Hold Status')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">My Hold Status</flux:heading>
        <flux:subheading>View your current hold status and history.</flux:subheading>

        {{-- Display error message if redirected by middleware --}}
        @if (session('error'))
            <div class="bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Access Restricted!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($activeHold)
            <div class="p-6 bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-bold mb-2">Account On Hold!</h3>
                <p class="mb-2">Your account is currently on hold. This may restrict access to certain services.</p>
                <p><strong>Reason:</strong> {{ $activeHold->reason }}</p>
                @if ($activeHold->description)
                    <p><strong>Details:</strong> {{ $activeHold->description }}</p>
                @endif
                <p class="text-sm mt-2">Placed on: {{ $activeHold->placed_at->format('d M Y H:i') }}</p>
                @if ($activeHold->placedBy)
                    <p class="text-sm">Placed by: {{ $activeHold->placedBy->name }}</p>
                @endif
            </div>
        @else
            <div class="p-6 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 rounded-lg shadow-md mb-6">
                <p>You currently have no active holds on your account.</p>
            </div>
        @endif

        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mt-4 mb-4">Hold History</h3>

        @if ($allHolds->isEmpty())
            <p class="text-gray-600 dark:text-gray-400 p-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md">No hold history found.</p>
        @else
            <div class="mt-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reason</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Placed At</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Released At</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach($allHolds as $hold)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $hold->reason }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $hold->placed_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $hold->released_at?->format('d M Y H:i') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        @if ($hold->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">Active</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">Released</span>
                                        @endif
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
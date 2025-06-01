<x-layouts.app :title="__('Fees')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Fees & Transaction Information</flux:heading>
        <flux:subheading>View your semester-wise financial records here.</flux:subheading>

        {{-- Overall Total Amount --}}
        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4">
            Overall Total Amount: <span class="text-teal-600 dark:text-teal-400">${{ number_format($totalAmount, 2) }}</span>
        </p>

        @forelse ($transactionsBySemester as $semesterName => $semesterTransactions)
            <div class="mt-8 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 p-4 border-b dark:border-zinc-700">{{ $semesterName }}</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reference Number</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Courses</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach($semesterTransactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $transaction->reference_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">${{ number_format($transaction->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($transaction->status == 'completed') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                            @elseif($transaction->status == 'failed') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                            @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        @forelse ($transaction->courses as $course)
                                            {{ $course->course_code }}<br>
                                        @empty
                                            <span class="text-gray-400 dark:text-gray-500">No courses linked</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $transaction->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                            {{-- Semester Subtotal Row --}}
                            <tr class="bg-gray-100 dark:bg-zinc-700 font-semibold">
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-200" colspan="2">Semester Total:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-900 dark:text-gray-200">${{ number_format($semesterTransactions->sum('amount'), 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap" colspan="3"></td> {{-- Empty cells for alignment --}}
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-400 p-4 bg-white dark:bg-zinc-800 rounded-lg shadow-md">No transactions found for your account.</p>
        @endforelse

    </div>
</x-layouts.app>
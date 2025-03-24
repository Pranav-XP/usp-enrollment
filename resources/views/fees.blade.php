<x-layouts.app :title="__('Fees')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Course Information</flux:heading>
        <flux:subheading>View course information here for your program.</flux:subheading>
        <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200 shadow-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <th class="p-3 border border-gray-200">#</th>
                    <th class="p-3 border border-gray-200">Reference Number</th>
                    <th class="p-3 border border-gray-200">Student ID</th>
                    <th class="p-3 border border-gray-200">Course</th>
                    <th class="p-3 border border-gray-200">Amount</th>
                    <th class="p-3 border border-gray-200">Status</th>
                    <th class="p-3 border border-gray-200">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr class="text-gray-700 text-sm border-b hover:bg-gray-50">
                        <td class="p-3 text-center border border-gray-200">{{ $loop->iteration }}</td>
                        <td class="p-3 text-center border border-gray-200">{{ $transaction->reference_number }}</td>
                        <td class="p-3 text-center border border-gray-200">{{ $transaction->student_id }}</td>
                        <td class="p-3 text-center border border-gray-200">{{ $transaction->course->name ?? 'N/A' }}</td>
                        <td class="p-3 text-center border border-gray-200">${{ number_format($transaction->amount, 2) }}</td>
                        <td class="p-3 text-center border border-gray-200">
                            <span class="px-3 py-1 rounded-full text-white text-xs font-bold
                                @if($transaction->status == 'completed') bg-green-500
                                @elseif($transaction->status == 'failed') bg-red-500
                                @else bg-yellow-500
                                @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="p-3 text-center border border-gray-200">{{ $transaction->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> 
    </div>
</x-layouts.app>

<x-layouts.app :title="__('Fees')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Course Information</flux:heading>
        <flux:subheading>View course information here for your program.</flux:subheading>
        <div class="overflow-x-auto">
        <table class="w-full border-collapse border ">
            <thead>
                <tr class=" text-sm">
                    <th class="p-3 border ">#</th>
                    <th class="p-3 border ">Reference Number</th>
                    <th class="p-3 border ">Student ID</th>
                    <th class="p-3 border ">Course</th>
                    <th class="p-3 border ">Amount</th>
                    <th class="p-3 border ">Status</th>
                    <th class="p-3 border ">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr class="">
                        <td class="p-3 text-center border ">{{ $loop->iteration }}</td>
                        <td class="p-3 text-center border ">{{ $transaction->reference_number }}</td>
                        <td class="p-3 text-center border ">{{ $transaction->student_id }}</td>
                        <td class="p-3 text-center border ">{{ $transaction->course->course_title ?? 'N/A' }}</td>
                        <td class="p-3 text-center border ">${{ number_format($transaction->amount, 2) }}</td>
                        <td class="p-3 text-center border">
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

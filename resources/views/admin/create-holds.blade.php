{{-- resources/views/admin/students/holds/create.blade.php --}}
<x-layouts.app :title="__('Place Hold')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Place Hold on {{ $student->first_name }} {{ $student->last_name }}</flux:heading>
        <flux:subheading>Fill in the details to place a new hold on this student's account.</flux:subheading>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error')) {{-- Also check for session error if redirecting back after a catch block --}}
            <div class="bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="mt-4 p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-md">
    <form action="{{ route('admin.holds.store', $student) }}" method="POST">
        @csrf

        {{-- Hold Reason Field --}}
        <flux:field class="mb-4"> {{-- Added mb-4 for spacing --}}
            <flux:label>Hold Reason <span class="text-red-500">*</span></flux:label>
            <flux:input type="text" name="reason" id="reason" value="{{ old('reason') }}" required />
            <flux:error name="reason" />
        </flux:field>


        <div class="flex justify-end gap-2">
            <flux:button variant="danger" href="{{ route('admin.holds.index',$student) }}">
                Cancel
            </flux:button>
            <flux:button variant="primary" type="submit">
                Place Hold
            </flux:button>
        </div>
    </form>
</div>
    </div>
</x-layouts.app>
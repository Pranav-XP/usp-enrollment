<x-layouts.app :title="__('Admin: Edit Semester')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Edit Semester: {{ $semester->name }}</flux:heading>
        <flux:subheading>
            Modify the details of this academic semester.
        </flux:subheading>

        {{-- Error Message --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.semesters.update', $semester) }}" method="POST" class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md space-y-6">
            @csrf
            @method('PUT') {{-- Use PUT method for update --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Year --}}
                <flux:field>
                    <flux:label for="year">Year <span class="text-red-500">*</span></flux:label>
                    <flux:input id="year" type="number" name="year" value="{{ old('year', $semester->year) }}" required min="1900" max="{{ date('Y') + 10 }}" />
                    <flux:error name="year" />
                </flux:field>

                {{-- Term --}}
                <flux:field>
                    <flux:label for="term">Term <span class="text-red-500">*</span></flux:label>
                    <flux:select id="term" name="term" required>
                        <option value="">Select Term</option>
                        @foreach($termOptions as $option)
                            <option value="{{ $option }}" {{ old('term', $semester->term) == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="term" />
                </flux:field>
            </div>

            {{-- Name (e.g., 2025 Semester 1) --}}
            <flux:field>
                <flux:label for="name">Semester Name <span class="text-red-500">*</span></flux:label>
                <flux:input id="name" type="text" name="name" value="{{ old('name', $semester->name) }}" placeholder="e.g., 2025 Semester 1" required />
                <flux:error name="name" />
            </flux:field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Start Date --}}
                <flux:field>
                    <flux:label for="start_date">Start Date <span class="text-red-500">*</span></flux:label>
                    <flux:input id="start_date" type="date" name="start_date" value="{{ old('start_date', $semester->start_date->format('Y-m-d')) }}" required />
                    <flux:error name="start_date" />
                </flux:field>

                {{-- End Date --}}
                <flux:field>
                    <flux:label for="end_date">End Date <span class="text-red-500">*</span></flux:label>
                    <flux:input id="end_date" type="date" name="end_date" value="{{ old('end_date', $semester->end_date->format('Y-m-d')) }}" required />
                    <flux:error name="end_date" />
                </flux:field>
            </div>

            <div class="flex justify-end mt-6 space-x-4">
                <flux:button href="{{ route('admin.semesters.index') }}" variant="secondary">
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Update Semester
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
<div>
    <form wire:submit="save" class="flex flex-col gap-6 mb-8">
        <flux:heading size='xl'>Create Program</flux:heading>
        <flux:subheading>Enter program information below.</flux:subheading>
        <!-- Program Code -->
        <flux:input
            wire:model="form.program_code"
            :label="__('Program code')"
            type="text"
            required
            autofocus
            :placeholder="__('BXXX')"
        />
    
        <!-- Program Name -->
        <flux:input
            wire:model="form.name"
            :label="__('Program name')"
            type="text"
            required
            autofocus
            :placeholder="__('Bachelors of XXXX')"
        />
    
            <!-- Description -->
        <flux:textarea
        wire:model="form.description"
        :label="__('Program Description')"
        required
        autofocus
        :placeholder="__('Describe the program')"
        class="h-32"
        />
    
        <!-- Duration -->
        <flux:input
            wire:model="form.duration"
            :label="__('Duration of program in years')"
            type="number"
            required
            placeholder="Duration in years"
        />
    
        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create program') }}
            </flux:button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200 rounded-lg shadow-md">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Program Code</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Description</th>
                    <th class="border border-gray-300 px-4 py-2">Duration (Years)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $program->program_code }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $program->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $program->description }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $program->duration }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">No programs available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>


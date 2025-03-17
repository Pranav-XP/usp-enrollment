
<head>
    <title>{{ $title ?? 'Page Title' }}</title>
</head>

<div>
<form wire:submit="save" class="flex flex-col gap-6">
    <flux:heading>Manage Programs</flux:heading>
    <flux:subheading>Enter program information below.</flux:subheading>
    <!-- Program Code -->
    <flux:input
        wire:model="program_code"
        :label="__('Program Code')"
        type="text"
        required
        autofocus
        :placeholder="__('BXXX')"
    />

    <!-- Program Name -->
    <flux:input
        wire:model="name"
        :label="__('Program Name')"
        type="text"
        required
        autofocus
        :placeholder="__('Bachelors of XXXXX')"
    />

    <!-- Description -->
    <flux:input
        wire:model="description"
        :label="__('Program Description')"
        type="text"
        required
        autofocus
        :placeholder="__('Describe the program')"
    />

    <!-- Duration -->
    <flux:input
        wire:model="duration"
        :label="__('Duration')"
        type="number"
        required
        placeholder="Duration of program in years"
        min="1"
        max="10"
    />

    <div class="flex items-center justify-end">
        <flux:button type="submit" variant="primary" class="w-full">
            {{ __('Create program') }}
        </flux:button>
    </div>
</form>

 <!-- Programs Table -->
 <div class="mt-8">
    <flux:heading size="lg">Programs List</flux:heading>

    @if($programs->isEmpty())
        <p class="text-gray-500 mt-2">No programs available. Add a new program above.</p>
    @else
        <div class="overflow-x-auto mt-4">
            <table class="w-full border border-gray-200 rounded-lg shadow-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">Program Code</th>
                        <th class="px-4 py-2 border">Program Name</th>
                        <th class="px-4 py-2 border">Description</th>
                        <th class="px-4 py-2 border">Duration (Years)</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $program)
                        <tr class="text-center border">
                            <td class="px-4 py-2">{{ $program->program_code }}</td>
                            <td class="px-4 py-2">{{ $program->name }}</td>
                            <td class="px-4 py-2">{{ $program->description }}</td>
                            <td class="px-4 py-2">{{ $program->duration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
</div>

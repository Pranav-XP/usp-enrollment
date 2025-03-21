<form wire:submit="save" class="flex flex-col gap-6">
    <flux:heading>Create Program</flux:heading>
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
    <flux:input
        wire:model="form.description"
        :label="__('Program description')"
        type="text"
        required
        autofocus
        :placeholder="__('Describe the program')"
    />

    <!-- Duration -->
    <flux:input
        wire:model="form.duration"
        :label="__('Duration of program in years')"
        type="number"
        required
        autocomplete="email"
        placeholder="email@example.com"
    />

    <div class="flex items-center justify-end">
        <flux:button type="submit" variant="primary" class="w-full">
            {{ __('Create program') }}
        </flux:button>
    </div>
</form>

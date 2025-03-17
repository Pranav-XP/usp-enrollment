
<head>
    <title>{{ $title ?? 'Page Title' }}</title>
</head>

<div>
<form wire:submit="save" class="flex flex-col gap-6">
    <flux:heading>Manage Courses</flux:heading>
    <flux:subheading>Enter course information below.</flux:subheading>
    <!-- Program Code -->
    <flux:input
        wire:model="course_code"
        :label="__('Course Code')"
        type="text"
        required
        autofocus
        :placeholder="__('AA111')"
    />

    <!-- Program Name -->
    <flux:input
        wire:model="course_title"
        :label="__('Course Title')"
        type="text"
        required
        autofocus
        :placeholder="__('Introduction to xxxxx')"
    />

    <!-- Description -->
    <flux:input
        wire:model="description"
        :label="__('Course Description')"
        type="text"
        required
        autofocus
        :placeholder="__('Describe the course')"
    />

    <!-- Duration -->
    <flux:input
        wire:model="cost"
        :label="__('Fees Amount')"
        type="number"
        required
        placeholder="Fees amount"
        min="1"
        max="10000"
        step="0.01"
    />
{{-- 
    <flux:checkbox wire:model="semester_1"
    required 
    :label="__('Available in Semester 1')"/>

    <flux:checkbox wire:model="semester_2"
    required 
    :label="__('Available in Semester 2')"/> --}}


    <div class="flex items-center justify-end">
        <flux:button type="submit" variant="primary" class="w-full">
            {{ __('Create course') }}
        </flux:button>
    </div>
</form>
</div>

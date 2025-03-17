<form wire:submit="register" class="flex flex-col gap-6">
    <flux:heading>Register Student</flux:heading>
<flux:subheading>Register student information below.</flux:subheading>
    <!-- First Name -->
    <flux:input
        wire:model="first_name"
        :label="__('First Name')"
        type="text"
        required
        autofocus
        autocomplete="name"
        :placeholder="__('First name')"
    />

    <!-- Last Name -->
    <flux:input
        wire:model="last_name"
        :label="__('Last Name')"
        type="text"
        required
        autofocus
        autocomplete="name"
        :placeholder="__('Last name')"
    />

    <!-- Student ID -->
    <flux:input
        wire:model="student_id"
        :label="__('Student ID')"
        type="text"
        required
        autofocus
        :placeholder="__('SXXXXXXXX')"
    />

    <!-- Email Address -->
    <flux:input
        wire:model="email"
        :label="__('Email address')"
        type="email"
        required
        autocomplete="email"
        placeholder="email@example.com"
    />

    <!-- DOB -->
    <flux:input
        wire:model="dob"
        :label="__('Date of Birth')"
        type="date"
        required
        max="2999-12-31"
    />

    <!-- Phone Number -->
    <flux:input
        wire:model="phone"
        :label="__('Phone number')"
        type="phone"
        required
        autocomplete="phone"
        placeholder="777 7777"
    />

    <!-- Password -->
    <flux:input
        wire:model="password"
        :label="__('Password')"
        type="password"
        required
        autocomplete="new-password"
        :placeholder="__('Password')"
    />

    <!-- Confirm Password -->
    <flux:input
        wire:model="password_confirmation"
        :label="__('Confirm password')"
        type="password"
        required
        autocomplete="new-password"
        :placeholder="__('Confirm password')"
    />

    <div class="flex items-center justify-end">
        <flux:button type="submit" variant="primary" class="w-full">
            {{ __('Create account') }}
        </flux:button>
    </div>
</form>

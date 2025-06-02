@if ($generalError)
    <div class="text-red-500 bg-red-100 border border-red-400 p-3 rounded mb-4">
        {{ $generalError }}
    </div>
@endif
<form wire:submit="register" class="flex flex-col gap-6">
    <flux:heading size="xl">Register Student</flux:heading>
    <flux:subheading>Register student information below.</flux:subheading>

    <flux:input
        wire:model="first_name"
        :label="__('First Name')"
        type="text"
        required
        autofocus
        autocomplete="given-name"
        :placeholder="__('First name')"
    />

    <flux:input
        wire:model="last_name"
        :label="__('Last Name')"
        type="text"
        required
        autocomplete="family-name"
        :placeholder="__('Last name')"
    />

    <flux:input
        wire:model="student_id"
        :label="__('Student ID')"
        type="text"
        required
        :placeholder="__('SXXXXXXXX')"
        autocomplete="username"
    />

    <flux:select wire:model="programId" placeholder="Assign program">
        @foreach ($programs as $program)
            <flux:select.option value="{{ $program->id }}">{{ $program->name}}</flux:select.option>
        @endforeach
    </flux:select>

    <flux:input
        wire:model="dob"
        :label="__('Date of Birth')"
        type="date"
        required
        max="2999-12-31"
    />

    <flux:input
        wire:model="phone"
        :label="__('Phone number')"
        type="tel" {{-- Changed type to tel for phone numbers --}}
        required
        autocomplete="tel"
        placeholder="777 7777"
    />

    <flux:input
        wire:model="postal_address"
        :label="__('Postal Address')"
        type="text"
        autocomplete="postal-code" {{-- Appropriate autocomplete for postal address --}}
        :placeholder="__('P.O. Box 123, City, Country')"
    />

    <flux:input
        wire:model="residential_address"
        :label="__('Residential Address')"
        type="text"
        autocomplete="street-address" {{-- Appropriate autocomplete for residential address --}}
        :placeholder="__('House No, Street, City, Country')"
    />

    {{-- Password and Confirm Password fields are removed as password is now student ID --}}

    <div class="flex items-center justify-end">
        <flux:button type="submit" variant="primary" class="w-full">
            {{ __('Create account') }}
        </flux:button>
    </div>
</form>
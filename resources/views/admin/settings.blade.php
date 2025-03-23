<x-layouts.app :title="__('Enrolment Settings')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading>Enrolment Settings</flux:heading>
        <flux:subheading>Toggle enrolments on & off</flux:subheading>

        <form method="post" action="{{ route('settings.update') }}">
            @csrf
            @foreach($settings as $setting)
                <div class="flex items-center gap-3 mb-2">
                    <label for="{{ $setting->key }}">{{ ucfirst(str_replace('_', ' ', $setting->key)) }}</label>
                    
                    @if($setting->key === 'users_can_enrol')
                        <input type="hidden" name="{{ $setting->key }}" value="0"> 
                        <input type="checkbox" name="{{ $setting->key }}" value="1" 
                            id="{{ $setting->key }}" 
                            {{ $setting->value == '1' ? 'checked' : '' }}>
                    @else
                        <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}">
                    @endif
                </div>
            @endforeach
            <flux:button variant="primary" type="submit">Save Changes</flux:button>
        </form>

    </div>
</x-layouts.app>

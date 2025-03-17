<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Welcome {{ auth()->user()->name }}</flux:heading>
<flux:subheading>This information will be displayed publicly.</flux:subheading>
    </div>
</x-layouts.app>

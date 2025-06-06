<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <div class="flex-col justify-between grow">
                <flux:navlist variant="outline">
                    <flux:navlist.group :heading="__('Menu')" class="grid">
                        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        {{-- Student Navigation Links --}}
                        @hasrole('student')
                        <flux:navlist.item icon="list-bullet" :href="route('courses')" :current="request()->routeIs('courses')" wire:navigate>{{ __('Courses') }}</flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document-check" :href="route('grades')" :current="request()->routeIs('grades')" wire:navigate>{{ __('My Grades') }}</flux:navlist.item>
                        <flux:navlist.item icon="currency-dollar" :href="route('transactions')" :current="request()->routeIs('transactions')" wire:navigate>{{ __('My Fees') }}</flux:navlist.item>
                        <flux:navlist.item icon="lock-closed" :href="route('student.holds')" :current="request()->routeIs('student.holds')" wire:navigate>{{ __('My Holds') }}</flux:navlist.item>
                        <flux:navlist.item icon="academic-cap" :href="route('graduation.create')" :current="request()->routeIs('graduation.create')" wire:navigate>{{ __('Graduation Application') }}</flux:navlist.item>
                        <flux:navlist.item icon="academic-cap" :href="route('pass.create')" :current="request()->routeIs('pass.create')" wire:navigate>{{ __('Special Pass Application') }}</flux:navlist.item>
                        @endhasrole
                        
                        {{-- Admin Navigation Links --}}
                        @hasrole('admin')
                        <flux:navlist.item icon="user-plus" :href="route('admin.register-student')" :current="request()->routeIs('admin.register-student')" wire:navigate>{{ __('Register Student') }}</flux:navlist.item>
                        
                        
                        <flux:navlist.item icon="adjustments-horizontal" 
                        :href="route('settings.edit')" 
                        :current="request()->routeIs('settings.edit')" 
                        wire:navigate>{{ __('SAS Settings') }}</flux:navlist.item>
                        
                        
                        <flux:navlist.item icon="pencil" 
                        :href="route('admin.students')" 
                        :current="request()->routeIs('admin.students')" 
                        wire:navigate>{{ __('Manage Students') }}</flux:navlist.item>

                        <flux:navlist.item icon="clipboard-document-check" 
                        :href="route('admin.recheck.index')" 
                        :current="request()->routeIs('admin.recheck.index')" 
                        wire:navigate>{{ __('Grade Recheck') }}</flux:navlist.item>

                        <flux:navlist.item icon="academic-cap" 
                        :href="route('admin.graduation.index')" 
                        :current="request()->routeIs('admin.graduation.index')" 
                        wire:navigate>{{ __('Graduation Applications') }}</flux:navlist.item>

                        <flux:navlist.item icon="document" 
                        :href="route('admin.pass.index')" 
                        :current="request()->routeIs('admin.pass.index')" 
                        wire:navigate>{{ __('Special Pass Applications') }}</flux:navlist.item>

                        <flux:navlist.item icon="calendar" 
                        :href="route('admin.semesters.index')" 
                        :current="request()->routeIs('admin.semesters.index')" 
                        wire:navigate>{{ __('Manage Semester') }}</flux:navlist.item>

                        
                        {{-- <flux:navlist.item icon="list-bullet" :href="route('course.create')" :current="request()->routeIs('course.create')" wire:navigate>{{ __('Manage courses') }}</flux:navlist.item> --}}
                        @endhasrole
                    </flux:navlist.group>
                </flux:navlist>
                
                {{-- New section to display Active Semester --}}
                @if ($activeSemester)
                <div class="px-4 py-2 mt-4 text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-400">
                    Current Semester
                </div>
                <div class="px-4 text-sm text-zinc-700 dark:text-zinc-200">
                    {{ $activeSemester->year }} - Semester {{ $activeSemester->term }}
                </div>
                @else
                <div class="px-4 py-2 mt-4 text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-400">
                    Semester Status
                </div>
                <div class="px-4 text-sm text-zinc-700 dark:text-zinc-200">
                    No active semester
                </div>
                @endif
                {{-- End new section --}}
            </div>
                
                <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="circle-help" href="https://www.usp.ac.fj/sas/student-administrative-services/frequently-asked-questions/" target="_blank">
                {{ __('FAQ') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>

<x-layouts.app :title="__('Courses')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Course Information</flux:heading>
        <flux:subheading>View course information here for your program.</flux:subheading>

        @if(session('error'))
    <div class="bg-red-500 text-white p-3 rounded">
        {{ session('error') }}
    </div>
@endif

@if(isset($program))
    <div>
        <flux:heading size="lg">Program: {{ $program->name }}</flux:heading>

        @if($program->courses->count() == 0)
            <p class="text-gray-500">No courses available for this program.</p>
        @else
            <ul class="space-y-3">
                @foreach ($program->courses as $course)
                <li class="p-3 rounded-md">
                    <h3 class="font-semibold">{{ $course->course_code }} - {{ $course->course_title }}</h3>
                    <p class="text-sm">{{ $course->description }}</p>

                                    {{-- Display Prerequisites --}}
                    @if($course->prerequisites->isNotEmpty())
                    <p class="mt-2 text-sm font-semibold text-accent">Prerequisites:</p>
                    @foreach($course->prerequisites as $prerequisite)
                        @php
                            // Decode the prerequisite_groups JSON into an array
                            $prerequisiteGroups = $prerequisite->prerequisite_groups; 
                        @endphp
                        
                        @if(is_array($prerequisiteGroups))
                            @foreach($prerequisiteGroups as $group)
                                {{-- Check if the group is an array and implode the values --}}

                                <p class="text-sm">
                                    {{ is_array($group) ? implode(' or ', $group) : $group }}
                                </p>
                            @endforeach
                        @else
                            {{-- If no nested array, just output the value --}}
                            <p class="text-sm text-gray-100">{{ $prerequisiteGroups }}</p>
                        @endif
                    @endforeach
                    @else
                    <p class="mt-2 text-sm text-gray-500">No prerequisites required.</p>
                    @endif

                </li>
                <flux:separator/>
            @endforeach
            </ul>
        @endif
    </div>
@endif
    </div>
</x-layouts.app>

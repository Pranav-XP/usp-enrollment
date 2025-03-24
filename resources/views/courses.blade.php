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
        <h3 class="text-2xl font-semibold text-teal-800 dark:text-teal-300 mb-2">Program: {{ $program->name }}</h3>
        <p class="text-base">{{ $program->description }}</p>
        @if($program->courses->count() == 0)
            <p class="text-gray-500">No courses available for this program.</p>
        @else
            @php
                // Group courses by the 'year' attribute
                $groupedCourses = $program->courses->groupBy('year');
            @endphp

            @foreach ($groupedCourses as $year => $courses)
                <div class="mt-6">
                    
                    <button 
                    class="w-full dark:bg-slate-700 bg-gray-200 p-2 mb-2 rounded-md cursor-pointer text-left text-2xl font-semibold text-teal-800 dark:text-teal-300 flex items-center justify-between"
                    onclick="toggleYearSection({{ $year }})">
                    <span>Year {{ $year }}</span>
                    <span id="icon-{{ $year }}" class="transition-transform duration-200">
                        <flux:icon.chevron-up-down />
                    </span>
                </button>
                   
                <p class="text-sm dark:text-gray-400 text-gray-900">75% of previous level courses must be completed.</p>    
                
                    <div id="year-{{ $year }}" class="hidden space-y-3">
                    <ul class="space-y-3">
                        @foreach ($courses as $course)
                            <li class="p-3 rounded-md">
                                <h3 class="font-semibold">{{ $course->course_code }} - {{ $course->course_title }}</h3>
                                <p class="text-sm">{{ $course->description }}</p>

                                     {{-- Display Semester Information --}}
                                     <div class="mt-2">
                                        @if($course->semester_1)
                                            <flux:badge color="teal" variant="solid" size="sm" icon="check-circle" class="mb-1">Offered in Semester 1</flux:badge>
                                        @endif
                                        @if($course->semester_2)
                                        <flux:badge color="teal" variant="solid" size="sm" icon="check-circle" class="mb-1">Offered in Semester 2</flux:badge>
                                        @endif
                                    </div>

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
                                    <p class="mt-2 text-sm text-gray-500">No course prerequisites required.</p>
                                @endif
                            </li>
                            <flux:separator/>
                        @endforeach
                    </ul>
                </div>
                </div>
            @endforeach
        @endif
    </div>

    <script>
        function toggleYearSection(year) {
            const yearSection = document.getElementById('year-' + year);
            // Toggle the visibility of the section
            if (yearSection.classList.contains('hidden')) {
                yearSection.classList.remove('hidden');
            } else {
                yearSection.classList.add('hidden');
            }
        }
    </script>
@endif
    </div>
</x-layouts.app>

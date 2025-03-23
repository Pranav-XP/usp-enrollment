<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if(session('success'))
    <div class="bg-green-500 text-white p-4 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-500 text-white p-4 rounded-md mb-4">
        {{ session('error') }}
    </div>
@endif

@hasrole('student')
        <flux:heading size="xl">Welcome {{ auth()->user()->name }}</flux:heading>
        <flux:subheading>View courses below.</flux:subheading>
        <div class="p-4">
            <h2 class="text-xl font-semibold mb-4">Enrolled Courses</h2>
            <div class="space-y-4">
                @foreach ($enrolledCourses as $course)
                <div class="bg-accent dark:bg-accent-foreground text-accent-foreground dark:text-gray-950 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 relative">
                    
                    <flux:badge color="lime" variant="solid" class="absolute top-2 right-2">
                        Enrolled
                </flux:badge>
                
                    <h3 class="text-lg font-medium">{{ $course->course_code }} -

                        @if($course->semester_1)
                        Semester 1
                        @else
                        Semester 2
                        @endif
                    </h3>
                    <p class="text-sm">{{ $course->course_title }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="p-4">
            <flux:heading size="xl" class="mb-2">Available Courses</flux:heading>
            @if($enrollmentSetting == 0)
            <flux:subheading class="mb-2">Enrolment closed by SAS.</flux:subheading>
            @else
            <flux:subheading class="mb-2">Enrol below.</flux:subheading>
            
            @foreach ($checkedCourses->groupBy('year') as $year => $courses)
                <div class="mb-8">
                    
                    <!-- Collapsible Year Section -->
                    <button class="w-full cursor-pointer text-lg font-medium text-left bg-gray-200 p-2 rounded-lg hover:bg-gray-300 focus:outline-none dark:bg-zinc-600 dark:hover:bg-zinc-500"
                            onclick="toggleCourses({{ $year }})">
                        Year {{ $year }}
                    </button>
        
                    <!-- Courses for this year, initially hidden -->
                    <div id="courses-year-{{ $year }}" class="space-y-4 mt-4 hidden">
                        @foreach ($courses as $course)
                            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                <div class="flex justify-between">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-800">{{ $course->course_code }}</h4>
                                        <p class="text-sm text-gray-600">{{ $course->course_title }}</p>
                                    </div>
                                    <div>
                                        @if(!$course->prerequisites_met)
                                        <flux:badge color="red" variant="solid">
                                            Cannot Enrol
                                        </flux:badge>
                                        @else
                                        <form action="{{ route('enrol.course', $course->id) }}" method="POST">
                                            @csrf
                                            <flux:button
                                                variant="primary"
                                                class="cursor-pointer"
                                                type="submit">
                                                Enrol
                                            </flux:button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            @endif
        </div>
        
        <!-- Add the JavaScript to toggle the collapse/expand -->
        <script>
            function toggleCourses(year) {
                const coursesDiv = document.getElementById(`courses-year-${year}`);
                // Toggle the visibility of the courses section
                if (coursesDiv.classList.contains('hidden')) {
                    coursesDiv.classList.remove('hidden');
                } else {
                    coursesDiv.classList.add('hidden');
                }
            }
        </script>
    @endhasrole

@hasrole('admin')
<flux:heading size="xl">Welcome {{ auth()->user()->name }}</flux:heading>

@endrole

    </div>
</x-layouts.app>

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
                    <button 
                    class="w-full dark:bg-slate-700 bg-gray-200 p-2 mb-2 rounded-md cursor-pointer text-left text-2xl font-semibold text-teal-800 dark:text-teal-300 flex items-center justify-between"
                    onclick="toggleCourses({{ $year }})">
                    <span>Year {{ $year }}</span>
                    <span id="icon-{{ $year }}" class="transition-transform duration-200">
                        <flux:icon.chevron-up-down />
                    </span>
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
                                        <flux:badge color="red" variant="solid" size="sm" icon="exclamation-triangle">
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
<flux:subheading size="md">The admin features are:</flux:heading>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6">
    @foreach([
        ['title' => 'Register Student', 'description' => 'Add new students to the system and manage their details.'],
        ['title' => 'SAS Settings', 'description' => 'Configure system-wide academic settings.'],
        ['title' => 'Manage Grades', 'description' => 'Review and update student grades.']
    ] as $feature)
        <div class="p-6 bg-white border rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-700">{{ $feature['title'] }}</h3>
            <p class="text-sm text-gray-500 mt-2">{{ $feature['description'] }}</p>
        </div>
    @endforeach
</div>

@endrole

    </div>
</x-layouts.app>

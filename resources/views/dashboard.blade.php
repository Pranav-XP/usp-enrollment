<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar (Navigation Bar on the Left) -->
        <nav class="w-64 bg-blue-900 text-white shadow-md">
            <div class="p-6">
                <!-- Branding -->
                <h1 class="text-xl font-bold">Student Portal</h1>
            </div>

            <!-- Navigation Links -->
            <div class="mt-6">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-2 text-sm font-medium hover:bg-blue-800">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}" class="flex items-center px-6 py-2 text-sm font-medium hover:bg-blue-800">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Courses
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('grades.index') }}" class="flex items-center px-6 py-2 text-sm font-medium hover:bg-blue-800">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                            </svg>
                            Grades
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('invoices.index') }}" class="flex items-center px-6 py-2 text-sm font-medium hover:bg-blue-800">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Invoices
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-6 py-2 text-sm font-medium hover:bg-blue-800">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Current Semester: Spring 2023</span>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Enrolled Courses -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-700">Enrolled Courses</h2>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-blue-900 mt-2">4</div>
                    <p class="text-xs text-gray-500">12 credit hours</p>
                </div>

                <!-- Current GPA -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-700">Current GPA</h2>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-blue-900 mt-2">3.75</div>
                    <p class="text-xs text-gray-500">Out of 4.0</p>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-700">Upcoming Deadlines</h2>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-blue-900 mt-2">2</div>
                    <p class="text-xs text-gray-500">Next: Course Add/Drop (3 days)</p>
                </div>

                <!-- Outstanding Balance -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-700">Outstanding Balance</h2>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-blue-900 mt-2">$1,250.00</div>
                    <p class="text-xs text-gray-500">Due: 04/15/2023</p>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="mt-6">
                <div class="flex space-x-4 border-b">
                    <button class="px-4 py-2 text-sm font-medium text-blue-900 border-b-2 border-blue-900">Current Courses</button>
                    <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-blue-900">Registration</button>
                    <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-blue-900">Grades</button>
                </div>

                <!-- Current Courses Tab -->
                <div class="mt-4">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-bold text-blue-900">Current Semester Courses</h2>
                        <p class="text-sm text-gray-500 mb-4">You are enrolled in 4 courses for the Spring 2023 semester.</p>

                        
                </div>
            </div>
        </main>
    </div>
</body>
</html>
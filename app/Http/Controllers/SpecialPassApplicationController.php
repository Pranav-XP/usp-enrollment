<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Course; // Assuming Course model exists for course_code
use App\Models\CourseStudent; // The pivot model provided by the user

class SpecialPassApplicationController extends Controller
{
    /**
     * Display the form for the "Application for Special Pass", autofilling student data.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user(); // Get the authenticated user

        // Initialize formData with default empty values for a single course
        $formData = [
            'studentIdNumber' => '',
            'fullName' => '',
            'email' => '',
            'dateOfBirth' => '',
            'telephone' => '',
            'postalAddress' => '',
            'campus' => '',
            'semesterTrimester' => '',
            'year' => date('Y'), // Default to current year
            'courseCode_1' => '',
            'examDate_1' => '',
            'examStartTime_1' => '',
            'applyingFor_1' => '',
            'reasons' => '',
            'declarationAgreed' => false,
        ];

        $completedCourseCodes = [];

        // If a student is authenticated and associated with the user, autofill details
        if ($user && $user->student) { // Assuming 'student' is the relationship method on the User model
            $student = $user->student;

            $formData['studentIdNumber'] = $student->student_id ?? '';
            $formData['fullName'] = ($student->first_name ?? '') . ' ' . ($student->last_name ?? '');
            $formData['email'] = $student->email ?? '';
            // Format date of birth for HTML date input
            $formData['dateOfBirth'] = $student->dob ? Carbon::parse($student->dob)->format('Y-m-d') : '';
            $formData['telephone'] = $student->phone ?? '';
            $formData['postalAddress'] = $student->postal_address ?? '';

            $completedEnrollments = CourseStudent::where('student_id', $user->student->id)
                ->where('status', 'completed')
                ->with(['course', 'semester']) // Eager load the related Course and Semester models
                ->get();

            foreach ($completedEnrollments as $enrollment) {
                if ($enrollment->course) {
                    // Assuming the Course model has a 'course_code' attribute
                    $completedCourseCodes[] = $enrollment->course->course_code;
                }
            }



            // Optionally prefill the first course code if available
            if (!empty($completedCourseCodes)) {
                $formData['courseCode_1'] = $completedCourseCodes[0];
            }
        }

        // Define options for dynamic fields
        $campuses = [
            'Laucala',
            'Alafua',
            'Emalus',
            'Kiribati',
            'Marshall Islands',
            'Nauru',
            'Niue',
            'Solomon Islands',
            'Tokelau',
            'Tonga',
            'Tuvalu',
            'Vanuatu',
            'Labasa',
            'Lautoka',
            'Savusavu',
        ];

        $semesterTrimesterOptions = [
            'Semester 1',
            'Semester 2',
            'Trimester 1',
            'Trimester 2',
            'Trimester 3',
            'Summer Semester',
            'Other Term'
        ];

        $applyingForOptions = [
            'Aegrotat Pass',
            'Compassionate Pass',
            'Special Exam'
        ];

        return view('special-pass', compact(
            'formData',
            'campuses',
            'semesterTrimesterOptions',
            'applyingForOptions',
            'completedCourseCodes' // Pass fetched completed course codes to the view
        ));
    }

    /**
     * Handle the submission of the "Application for Special Pass" form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Fetch completed courses again for validation purposes
        $completedCourseCodes = [];
        $user = Auth::user();
        if ($user && $user->student) {
            $student = $user->student;

            $completedEnrollments = CourseStudent::where('student_id', $user->student->id)
                ->where('status', 'completed')
                ->with(['course', 'semester']) // Eager load the related Course and Semester models
                ->get();

            foreach ($completedEnrollments as $enrollment) {
                if ($enrollment->course) {
                    $completedCourseCodes[] = $enrollment->course->course_code;
                }
            }
        }

        // 1. Validate incoming request data
        $rules = [
            'studentIdNumber' => ['required', 'string', 'max:255'],
            'fullName' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'campus' => ['required', 'string', Rule::in([
                'Laucala',
                'Alafua',
                'Emalus',
                'Kiribati',
                'Marshall Islands',
                'Nauru',
                'Niue',
                'Solomon Islands',
                'Tokelau',
                'Tonga',
                'Tuvalu',
                'Vanuatu',
                'Labasa',
                'Lautoka',
                'Savusavu',
            ])],
            'dateOfBirth' => ['required', 'date'],
            'telephone' => ['required', 'string', 'max:20'],
            'postalAddress' => ['nullable', 'string', 'max:500'],
            'semesterTrimester' => ['required', 'string', Rule::in([
                'Semester 1',
                'Semester 2',
                'Trimester 1',
                'Trimester 2',
                'Trimester 3',
                'Summer Semester',
                'Other Term'
            ])],
            'year' => ['required', 'integer', 'min:' . (date('Y') - 5), 'max:' . (date('Y') + 5)],

            // Section B: Missed Exam Details (only Course #1)
            'courseCode_1' => ['required', 'string', 'max:50', Rule::in($completedCourseCodes)], // Validate against fetched completed courses
            'examDate_1' => ['required', 'date'],
            'examStartTime_1' => ['required', 'date_format:H:i'],
            'applyingFor_1' => ['required', 'string', Rule::in(['Aegrotat Pass', 'Compassionate Pass', 'Special Exam'])],

            // Section C: Application Details
            'reasons' => ['required', 'string', 'max:1000'],
            'supportingDocuments' => ['required', 'array', 'min:1', 'max:7'], // At least 1, max 7 files
            'supportingDocuments.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:3072'], // Max 3MB per file
            'declarationAgreed' => ['accepted'], // Checkbox must be ticked
        ];

        // Custom validation message if optional fields are partially filled
        $messages = [
            'courseCode_1.in' => 'The selected course code is not a recognized completed course.',
            'examDate_1.required' => 'Exam Date (1) is required.',
            'examStartTime_1.required' => 'Exam Start Time (1) is required.',
            'applyingFor_1.required' => 'Applying For (1) is required.',
            'supportingDocuments.required' => 'At least one supporting document is required.',
            'supportingDocuments.max' => 'You can upload a maximum of 7 files.',
            'supportingDocuments.*.max' => 'Each file must not exceed 3MB.',
            'supportingDocuments.*.mimes' => 'Only PDF, DOC, DOCX, JPG, JPEG, PNG files are allowed.',
        ];

        $validatedData = $request->validate($rules, $messages);

        try {
            // 2. Prepare data for the microservice
            $applicationData = [
                'submittedAt' => Carbon::now('Pacific/Fiji')->toISOString(),
                'studentIdNumber' => $validatedData['studentIdNumber'],
                'fullName' => $validatedData['fullName'] ?? null,
                'email' => $validatedData['email'],
                'campus' => $validatedData['campus'],
                'dateOfBirth' => Carbon::parse($validatedData['dateOfBirth'])->format('Y-m-d'),
                'telephone' => $validatedData['telephone'],
                'postalAddress' => $validatedData['postalAddress'] ?? null,
                'semesterTrimester' => $validatedData['semesterTrimester'],
                'year' => (int) $validatedData['year'],
                'missedExams' => [],
                'reasons' => $validatedData['reasons'],
                'declarationAgreed' => true,
            ];

            // Add details for the single missed exam
            $applicationData['missedExams'][] = [
                'courseCode' => $validatedData['courseCode_1'],
                'examDate' => Carbon::parse($validatedData['examDate_1'])->format('Y-m-d'),
                'examStartTime' => $validatedData['examStartTime_1'],
                'applyingFor' => $validatedData['applyingFor_1'],
            ];

            // --- FILE UPLOADS HANDLING ---
            $uploadedFilePaths = [];
            if ($request->hasFile('supportingDocuments')) {
                foreach ($request->file('supportingDocuments') as $file) {
                    // Store the file temporarily in Laravel's storage (e.g., public/storage/special_pass_documents)
                    // Ensure you've run 'php artisan storage:link' for public disk to be accessible via URL
                    $path = $file->store('special_pass_documents', 'public');
                    $uploadedFilePaths[] = Storage::url($path); // Get URL if using public disk
                }
            }
            $applicationData['supportingDocumentUrls'] = $uploadedFilePaths; // Send URLs to microservice

            $microserviceUrl = 'http://localhost:3001/applications';

            // 4. Make an HTTP POST request to the microservice endpoint
            $response = Http::post($microserviceUrl, $applicationData);

            // 5. Handle the response
            if ($response->successful()) {
                return redirect()->back()->with('success', 'Special Pass Application submitted successfully!')->withFragment('top');
            } else {
                Log::error('Microservice error submitting Special Pass Application: ' . $response->body());
                return redirect()->back()->with('error', 'Failed to submit application. Please try again.')->withInput()->withFragment('top');
            }
        } catch (\Exception $e) {
            Log::error('Exception submitting Special Pass Application: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput()->withFragment('top');
        }
    }
}

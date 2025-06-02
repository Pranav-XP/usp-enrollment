<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Student;

class GraduationApplicationController extends Controller
{
    /**
     * Show the graduation application form, autofilling student data.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $student = $user->student;
        // Prepare initial data for autofill from the Student model
        $formData = [
            'studentIdNumber' => $student->student_id ?? '', // From Student model's student_id
            'name' => ($student->first_name ?? '') . ' ' . ($student->last_name ?? ''), // Concatenate first and last name
            'email' => $student->email ?? '',
            'dateOfBirth' => $student->dob ? Carbon::parse($student->dob)->format('Y-m-d') : '', // Formatted as Y-m-d for HTML date input
            'telephone' => $student->phone ?? '', // From Student model's phone
            'postalAddress' => $student->postal_address ?? '', // From Student model's postal_address

            // Fields that cannot be autofilled from Student model:
            'programmeType' => '',
            'programme' => $student->program->name ?? '', // Assuming programme is fetched from the related Program model
            'major1' => '', // Assuming these are not directly on Student model
            'major2' => '',
            'minor' => '',
            'graduationCeremonyVenue' => '',
            'willAttendGraduation' => null, // null for radio buttons initially
            'declarationAgreed' => false,
        ];

        return view('graduation-form', compact('formData'));
    }

    /**
     * Submit the graduation application data to the microservice.
     * (This method remains unchanged as it uses the validated form data)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validate the request data from your form
        $validatedData = $request->validate([
            'studentIdNumber' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'dateOfBirth' => 'required|date',
            'telephone' => 'required|string|max:255',
            'postalAddress' => 'required|string|max:255',
            'programmeType' => 'required|string|in:Undergraduate,Postgraduate,Pacific TAFE',
            'programme' => 'required|string|max:255',
            'major1' => 'required|string|max:255',
            'major2' => 'nullable|string|max:255',
            'minor' => 'nullable|string|max:255',
            'graduationCeremonyVenue' => 'required|string|in:Laucala,Solomon Islands,Tonga',
            'willAttendGraduation' => 'required|boolean',
            'declarationAgreed' => 'accepted',
        ]);

        $payload = [
            'studentIdNumber' => $validatedData['studentIdNumber'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'dateOfBirth' => Carbon::parse($validatedData['dateOfBirth'])->format('Y-m-d'),
            'telephone' => $validatedData['telephone'],
            'postalAddress' => $validatedData['postalAddress'],
            'programmeType' => $validatedData['programmeType'],
            'programme' => $validatedData['programme'],
            'major1' => $validatedData['major1'],
            'major2' => $validatedData['major2'],
            'minor' => $validatedData['minor'],
            'graduationCeremonyVenue' => $validatedData['graduationCeremonyVenue'],
            'willAttendGraduation' => (bool)$validatedData['willAttendGraduation'],
            'declarationAgreed' => true,
        ];

        try {
            $response = Http::post('http://localhost:8081/api/graduation-applications/submit', $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Graduation application submitted to Express microservice.', [
                    'student_id' => $payload['studentIdNumber'],
                    'response_id' => $responseData['application']['id'] ?? 'N/A'
                ]);

                return redirect()->route('dashboard')->with('success', 'Graduation application submitted successfully!');
            } else {
                $errorMessage = $response->json()['error'] ?? 'Unknown error from microservice.';
                Log::error('Express microservice error during graduation application submission: ' . $errorMessage, ['status' => $response->status(), 'body' => $response->body()]);
                return back()->with('error', 'Failed to submit application: ' . $errorMessage)->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Error connecting to Express graduation application microservice: ' . $e->getMessage());
            return back()->with('error', 'Could not connect to the application service. Please try again later.')->withInput();
        }
    }
}

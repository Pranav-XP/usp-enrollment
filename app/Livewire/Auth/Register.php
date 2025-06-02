<?php

namespace App\Livewire\Auth;

use App\Models\Program;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Import DB Facade
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Student;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
class Register extends Component
{
    public $programs;

    public string $student_id = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $dob = '';
    public string $phone = '';
    public string $postal_address = '';
    public string $residential_address = '';
    public $programId = 1;

    // Add a public property for general error messages
    public string $generalError = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // Reset any previous general error message
        $this->generalError = '';

        $validated = $this->validate([
            // Student Details
            'student_id' => ['required', 'string', 'max:20', 'unique:students,student_id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'phone' => ['required', 'string', 'max:15'],
            'postal_address' => ['nullable', 'string', 'max:500'],
            'residential_address' => ['nullable', 'string', 'max:500'],
        ]);

        // Automatically generate email from student ID
        $studentEmail = strtolower($this->student_id) . '@student.usp.ac.fj';

        // Validate the generated email for uniqueness
        try {
            $this->validate([
                'student_id' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('students', 'student_id'),
                    // Add a rule to ensure the generated email is unique for users
                    Rule::unique(User::class, 'email')->where(function ($query) use ($studentEmail) {
                        return $query->where('email', $studentEmail);
                    })
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw the validation exception so Livewire handles it normally
            throw $e;
        }


        // Wrap the creation process in a database transaction
        DB::transaction(function () use ($validated, $studentEmail) {
            // Automatically set password to hashed student_id
            $hashedPassword = Hash::make($this->student_id);

            // Combine first_name and last_name for User name
            $fullName = $validated['first_name'] . ' ' . $validated['last_name'];

            // Create User
            $user = User::create([
                'name' => $fullName,
                'email' => $studentEmail,
                'password' => $hashedPassword,
            ]);

            $user->assignRole('student');

            // Set the enrollment year to the current year
            $enrollment_year = Carbon::now()->year;

            // Create Student Record
            // If this fails, the transaction will be rolled back
            Student::create([
                'user_id' => $user->id,
                'student_id' => $validated['student_id'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $studentEmail,
                'dob' => $validated['dob'],
                'phone' => $validated['phone'],
                'postal_address' => $validated['postal_address'],
                'residential_address' => $validated['residential_address'],
                'program_id' => $this->programId,
                'enrollment_year' => $enrollment_year,
            ]);

            // If everything above succeeds, commit the transaction (implicitly by DB::transaction)
            event(new Registered($user));
            Auth::login($user);
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        }, 3); // The '3' is the number of times to retry the transaction on deadlock.

        // If an exception occurs within the transaction and is not caught by Livewire's validation,
        // it will prevent redirect and the transaction will rollback.
        // For general database errors (e.g., unexpected constraints), Livewire might
        // display a generic error, or you can add a catch block around DB::transaction
        // to set a more specific error message.
    }

    public function mount()
    {
        $this->programs = Program::all();
    }
}

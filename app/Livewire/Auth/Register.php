<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Student;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
class Register extends Component
{
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Student Fields
    public string $student_id = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $dob = '';
    public string $phone = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            // Student Details
            'student_id' => ['required', 'string', 'max:20', 'unique:students,student_id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'phone' => ['required', 'string', 'max:15'],

            // User Details
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Combine first_name and last_name for User name
        $fullName = $validated['first_name'] . ' ' . $validated['last_name'];

        
        // Create User
        $user = User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->assignRole('student');

        // Set the enrollment year to the current year
        $enrollment_year = Carbon::now()->year;

        // Create Student Record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $validated['student_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'dob' => $validated['dob'],
            'phone' => $validated['phone'],
            'enrollment_year' => $enrollment_year, // Automatically set enrollment year
        ]);
        event(new Registered($user));

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}

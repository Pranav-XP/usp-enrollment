<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Student;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
     /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                // Student Details
                'student_id' => ['required', 'string', 'max:20', 'unique:students,student_id'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'dob' => ['required', 'date'],
                'phone' => ['required', 'string', 'max:15'],
                // User Details
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required'],
                'program_id' => ['required', 'integer', 'exists:programs,id'], // Ensure program exists
            ]);

            // Hash password
            $validated['password'] = Hash::make($validated['password']);

            // Combine first_name and last_name for User name
            $fullName = $validated['first_name'] . ' ' . $validated['last_name'];

            // Begin Transaction
            DB::beginTransaction();

            // Create User
            $user = User::create([
                'name' => $fullName,
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'student', // Assign role as 'student'
            ]);

            // Set enrollment year to the current year
            $enrollmentYear = Carbon::now()->year;

            // Create Student Record
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $validated['student_id'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'dob' => $validated['dob'],
                'phone' => $validated['phone'],
                'program_id' => $validated['program_id'],
                'enrollment_year' => $enrollmentYear, // Automatically set enrollment year
            ]);

            // Commit transaction
            DB::commit();

            // Trigger Laravel Registered Event
            event(new Registered($user));

            return response()->json([
                'message' => 'Student registered successfully',
                'user' => $user,
                'student'=> $student,
            ], 201);

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Database error occurred during registration',
                'error' => $e->getMessage(),
            ], 500);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


     /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

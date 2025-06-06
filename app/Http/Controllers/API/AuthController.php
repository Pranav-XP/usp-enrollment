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


/**
 * @OA\Info(
 *     title="USPEnrol API",
 *     version="1.0.0",
 *     description="API documentation for USPEnrol",
 *
 *     @OA\Contact(
 *         email="s11171153@student.usp.ac.fj"
 *     )
 * )
 */


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new student",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"student_id", "first_name", "last_name", "dob", "phone", "email", "password", "program_id"},
     *             @OA\Property(property="student_id", type="string", example="S11171153"),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="dob", type="string", format="date", example="2000-01-01"),
     *             @OA\Property(property="phone", type="string", example="+6791234567"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@student.usp.ac.fj"),
     *             @OA\Property(property="password", type="string", format="password", example="securePass123"),
     *             @OA\Property(property="program_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Student registered successfully"),
     *     @OA\Response(response=500, description="Database error or unexpected error")
     * )
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
                'student' => $student,
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
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "device_name"},
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="securePass123"),
     *             @OA\Property(property="device_name", type="string", example="John's iPhone")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User logged in successfully"),
     *     @OA\Response(response=401, description="Invalid credentials or validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                    'device_name' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->with('student')->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => $user,
                'token' => $user->createToken($request->device_name)->plainTextToken,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

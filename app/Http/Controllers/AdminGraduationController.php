<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminGraduationController extends Controller
{
    /**
     * Display a listing of all graduation applications.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $response = Http::get('http://localhost:8081/api/graduation-applications');

            if ($response->successful()) {
                $applications = $response->json();
                return view('admin.graduations', compact('applications'));
            } else {
                $errorMessage = $response->json()['error'] ?? 'Unknown error from microservice.';
                Log::error('Admin: Failed to fetch all applications from Express microservice.', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->back()->with('error', 'Failed to load applications: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Admin: Error connecting to Express microservice for applications index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not connect to the application service. Please try again later.');
        }
    }

    /**
     * Display the specified graduation application details.
     *
     * @param  string  $id The ID of the application to view.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $id)
    {
        try {
            $response = Http::get("http://localhost:8081/api/graduation-applications/{$id}");

            if ($response->successful()) {
                $application = $response->json();
                return view('admin.graduation-detail', compact('application'));
            } else {
                $errorMessage = $response->json()['error'] ?? 'Unknown error from microservice.';
                Log::error('Admin: Failed to fetch application details from Express microservice.', [
                    'id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->route('admin.graduation.index')->with('error', 'Failed to load application details: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Admin: Error connecting to Express microservice for application details: ' . $e->getMessage());
            return redirect()->route('admin.graduation.index')->with('error', 'Could not connect to the application service. Please try again later.');
        }
    }
}

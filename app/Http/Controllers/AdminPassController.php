<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminPassController extends Controller
{
    /**
     * Display a listing of all special pass applications from the microservice.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $microserviceUrl = 'http://localhost:3001/applications';

        try {
            $response = Http::get($microserviceUrl);

            if ($response->successful()) {
                $applications = $response->json();
                return view('admin.pass', compact('applications'));
            } else {
                Log::error('Microservice error fetching applications list: ' . $response->body());
                return redirect()->back()->with('error', 'Failed to retrieve applications list. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Exception fetching applications list: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred while fetching applications. Please try again.');
        }
    }

    /**
     * Display the details of a specific special pass application.
     *
     * @param string $id The ID of the application to view.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $id)
    {
        $microserviceUrl = "http://localhost:3001/applications/{$id}";

        try {
            $response = Http::get($microserviceUrl);

            if ($response->successful()) {
                $application = $response->json();
                return view('admin.pass-details', compact('application'));
            } else if ($response->status() == 404) {
                return redirect()->route('admin.pass.index')->with('error', 'Application not found.');
            } else {
                Log::error("Microservice error fetching application {$id}: " . $response->body());
                return redirect()->route('admin.pass.index')->with('error', 'Failed to retrieve application details. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error("Exception fetching application {$id}: " . $e->getMessage());
            return redirect()->route('admin.pass.index')->with('error', 'An unexpected error occurred while fetching application details. Please try again.');
        }
    }
}

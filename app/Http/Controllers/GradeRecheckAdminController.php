<?php

namespace App\Http\Controllers;

use App\Enums\GradeRecheckStatus;
use App\Mail\RecheckApplicationStatusUpdatedMail;
use App\Mail\RecheckApplicationSubmittedMail;
use App\Models\GradeRecheckApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GradeRecheckAdminController extends Controller
{
    /**
     * Display a listing of grade recheck applications.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $applications = GradeRecheckApplication::with('student', 'course')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.recheck', compact('applications'));
    }

    /**
     * Display the specified grade recheck application details.
     *
     * @param  int  $id The ID of the GradeRecheckApplication.
     * @return \Illuminate\View\View
     */
    public function show(int $id)
    {
        // Find the application by ID and eager load student and course details.
        $application = GradeRecheckApplication::with('student', 'course')->findOrFail($id);

        return view('admin.recheck-details', compact('application'));
    }

    /**
     * Update the status of the specified grade recheck application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id The ID of the GradeRecheckApplication.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, int $id)
    {
        $application = GradeRecheckApplication::findOrFail($id);
        $originalStatus = $application->status;

        // Validate the incoming status and notes.
        $request->validate([
            'status'     => ['required', 'string', Rule::in(array_column(GradeRecheckStatus::cases(), 'value'))], // Validate against Enum values
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Update the application's status and admin notes.
        $application->status = $request->status;
        $application->admin_notes = $request->admin_notes;
        $application->save();

        // --- Send Email Notification if Status Changed ---
        if ($application->status !== $originalStatus) {
            Mail::to($application->email)->send(new RecheckApplicationStatusUpdatedMail($application));
        }

        // Redirect back to the application details page with a success message.
        return redirect()->route('admin.recheck.show', $application->id)
            ->with('success', 'Application status updated successfully.');
    }

    /**
     * Serve a private recheck payment confirmation file to an authorized administrator.
     *
     * @param int $applicationId The ID of the GradeRecheckApplication.
     * @return \Illuminate\Http\Response|StreamedResponse
     */
    public function downloadPaymentConfirmation(int $applicationId)
    {
        // Find the application by ID.
        $application = GradeRecheckApplication::findOrFail($applicationId);

        // Get the internal file path from the database.
        $filePath = $application->payment_confirmation_path;

        // Check if the file exists on the 'local' (private) disk.
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'File not found.');
        }
        return Storage::download($filePath, basename($filePath));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // For logging errors

class SemesterController extends Controller
{
    /**
     * Display a listing of the semesters.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $semesters = Semester::orderBy('year', 'desc')->orderBy('start_date', 'desc')->get();
        return view('admin.semesters.index', compact('semesters'));
    }

    /**
     * Show the form for creating a new semester.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $termOptions = [
            'Semester 1',
            'Semester 2',
            'Trimester 1',
            'Trimester 2',
            'Trimester 3',
            'Summer Semester',
            'Other Term'
        ];
        return view('admin.semesters.create', compact('termOptions'));
    }

    /**
     * Store a newly created semester in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'term' => ['required', 'string', Rule::in([
                'Semester 1',
                'Semester 2',
                'Trimester 1',
                'Trimester 2',
                'Trimester 3',
                'Summer Semester',
                'Other Term'
            ])],
            'name' => ['required', 'string', 'max:255', 'unique:semesters,name'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        try {
            Semester::create([
                'year' => $validatedData['year'],
                'term' => $validatedData['term'],
                'name' => $validatedData['name'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'is_active' => false, // New semesters are inactive by default
            ]);

            return redirect()->route('admin.semesters.index')->with('success', 'Semester created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating semester: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create semester. Please try again.')->withInput();
        }
    }

    /**
     * Show the form for editing the specified semester.
     *
     * @param  \App\Models\Semester  $semester
     * @return \Illuminate\View\View
     */
    public function edit(Semester $semester)
    {
        $termOptions = [
            'Semester 1',
            'Semester 2',
            'Trimester 1',
            'Trimester 2',
            'Trimester 3',
            'Summer Semester',
            'Other Term'
        ];
        return view('admin.semesters.edit', compact('semester', 'termOptions'));
    }

    /**
     * Update the specified semester in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Semester  $semester
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Semester $semester)
    {
        $validatedData = $request->validate([
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'term' => ['required', 'string', Rule::in([
                'Semester 1',
                'Semester 2',
                'Trimester 1',
                'Trimester 2',
                'Trimester 3',
                'Summer Semester',
                'Other Term'
            ])],
            'name' => ['required', 'string', 'max:255', Rule::unique('semesters')->ignore($semester->id)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        try {
            $semester->update($validatedData);

            return redirect()->route('admin.semesters.index')->with('success', 'Semester updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating semester: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update semester. Please try again.')->withInput();
        }
    }

    /**
     * Set the specified semester as active and deactivate all others.
     *
     * @param  \App\Models\Semester  $semester
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setActive(Semester $semester)
    {
        try {
            DB::transaction(function () use ($semester) {
                // Deactivate all other semesters
                Semester::where('is_active', true)->where('id', '!=', $semester->id)->update(['is_active' => false]);

                // Activate the selected semester
                $semester->update(['is_active' => true]);
            });

            return redirect()->route('admin.semesters.index')->with('success', 'Semester "' . $semester->name . '" has been set as active!');
        } catch (\Exception $e) {
            Log::error('Error setting active semester: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to set active semester. Please try again.');
        }
    }

    // You can also add a destroy method if you want to allow deleting semesters
    // public function destroy(Semester $semester)
    // {
    //     // Add logic to prevent deleting active semester or semesters with related data
    //     $semester->delete();
    //     return redirect()->route('admin.semesters.index')->with('success', 'Semester deleted successfully!');
    // }
}

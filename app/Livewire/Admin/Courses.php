<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Manage Courses')] 
class Courses extends Component
{
    public $course_code, $course_title, $description, $cost, $semester_1 = false, $semester_2 = false;
    public $courseIdToEdit = null;

    // Validation rules
    protected $rules = [
        'course_code' => 'required|string|max:255|unique:courses,course_code',
        'course_title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'cost' => 'required|numeric|min:0',
    ];

    public function render()
    {
        // Render the component and pass the courses list to the view
        $courses = Course::all();
        return view('livewire.admin.courses',compact('courses'));
    }

    // Store new course
    public function save()
    {
        $this->validate();

        Course::create([
            'course_code' => $this->course_code,
            'course_title' => $this->course_title,
            'description' => $this->description,
            'cost' => $this->cost,
            'semester_1' => $this->semester_1,
            'semester_2' => $this->semester_2,
        ]);

       // Reset form fields after saving
       $this->reset(['course_code', 'course_title', 'description', 'cost','semester_1','semester_2']);

        session()->flash('message', 'Course successfully created!');
    }
}

<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\CourseForm;
use App\Models\Course;
use App\Models\Program;
use Livewire\Component;

class Courses extends Component
{
    public CourseForm $form;
    public $courses;
    public $programs;

    public function mount(){
        $this->courses = Course::all();
        $this->programs= Program::all();
    }

    public function save(){
        $this->validate();

        $course = Course::create(
            $this->form->all()
        );
        
        return $this->redirect('/admin/course');
    }

    public function render()
    {
        return view('livewire.admin.courses');
    }
}

<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CourseForm extends Form
{
    #[Validate('required|string|max:10|unique:courses,course_code')]
    public $course_code;

    #[Validate('required|string|max:255')]
    public $course_title;

    #[Validate('nullable|string|max:500')]
    public $description;

    #[Validate('required|integer|min:1|max:6')]
    public $year;

    #[Validate('required|numeric|min:0')]
    public $cost;

    public $semester_1 = false;

    public $semester_2 = false;
}

<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ProgramForm extends Form
{
    #[Validate('required|string|unique:programs,program_code|max:10')]
    public $program_code;

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|string|max:1000')]
    public $description;

    #[Validate('required|integer|min:1|max:10')]
    public $duration;
}

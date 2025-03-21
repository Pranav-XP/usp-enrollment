<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\ProgramForm;
use App\Models\Program;

#[Layout('components.layouts.app')]
class CreateProgram extends Component
{
    public ProgramForm $form;
    public $programs;

    public function mount(){
        $this->programs = Program::all();
    }

    public function save(){
        $this->validate();

        Program::create(
            $this->form->all()
        );

        return $this->redirect('admin/program');
    }

    public function render()
    {
        return view('livewire.admin.create-program');
    }
}

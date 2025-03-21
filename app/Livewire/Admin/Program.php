<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\ProgramForm;
use Livewire\Component;
use Livewire\Attributes\Layout;


#[Layout('components.layouts.app')]
class Program extends Component
{
    public ProgramForm $form;

    public function save(){
        $this->validate();

        Program::create(
            $this->form->all()
        );

        return $this->redirect('admin.program');
    }


    public function render()
    {
        return view('livewire.admin.program');
    }
}

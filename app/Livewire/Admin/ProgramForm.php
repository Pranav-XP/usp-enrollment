<?php

namespace App\Livewire\Admin;

use App\Models\Program;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
class ProgramForm extends Component
{

    public string $program_code = '';
    public string $name = '';
    public string $description = '';
    public int $duration = 1;

    public $programs;
    
    public function mount()
    {
        $this->programs = Program::all();
    }

    public function save(){
        $validated = $this->validate([
        'program_code' => 'required|string|max:10|unique:programs,program_code',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'duration' => 'required|integer|min:1',
        ]);

        Program::create($validated);

         // Reset form fields after saving
         $this->reset(['program_code', 'name', 'description', 'duration']);

          // Refresh list
        $this->programs = Program::all();

         // Flash success message
         session()->flash('success', 'Program added successfully!');
    }


#[Title('Manage Programmes')] 
    public function render()
    {
        return view('livewire.admin.program-form');
    }
}

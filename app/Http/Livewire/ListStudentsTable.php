<?php

namespace App\Http\Livewire;

use App\Services\Student\StudentService;
use Livewire\Component;

class ListStudentsTable extends Component
{
    public $students;

    public function mount(StudentService $studentService)
    {
        $this->students = $studentService->getAllActiveStudents()->sortBy('name')->load('studentRecord', 'studentRecord.myClass', 'studentRecord.section');
    }

    public function render()
    {
        return view('livewire.list-students-table');
    }
}

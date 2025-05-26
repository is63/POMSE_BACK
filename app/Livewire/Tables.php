<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\DB;

class Tables extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $tableName;
    public $type ="usuario";
    public $columns;
    public $condition;

    protected $queryString = ['search'];


    public function mount()
    {
        $this->columns = DB::getSchemaBuilder()->getColumnListing($this->tableName);
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingCondition()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DB::table($this->tableName);

        // Permite buscar cualquier valor
        if ($this->type !== null && $this->condition !== null && $this->type !== '' && $this->condition !== '') {
            // Si el usuario escribe "\0", busca el carácter nulo real
            $condition = $this->condition === '\0' ? "\0" : $this->condition;
            $query->where($this->type, 'LIKE', "%{$condition}%");
        }

        $tableData = $query->paginate(10);

        return view('livewire.tables', [
            'tableData' => $tableData,
            'columns' => $this->columns,
        ]);
    }

    public function search()
    {
        $this->resetPage();
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Tables extends Component
{
    use WithPagination;

    public $tableName;
    public $type;
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

        if ($this->type && $this->condition) {
            $query->where($this->type, 'LIKE', "%{$this->condition}%");
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

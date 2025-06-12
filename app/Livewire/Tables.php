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
    public $type = "usuario";
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
        try {
            $query = DB::table($this->tableName);

            // Permite buscar cualquier valor
            if ($this->type !== null && $this->condition !== null && $this->type !== '' && $this->condition !== '') {
                // Si el usuario escribe "\0", busca el carácter nulo real
                $condition = $this->condition === '\0' ? "\0" : $this->condition;
                $query->where($this->type, 'LIKE', "%{$condition}%");
            }

            $tableData = $query->paginate(10);
        } catch (\Exception $e) {
            // Si hay error en la consulta, devuelve la tabla completa sin filtro
            $tableData = DB::table($this->tableName)->paginate(10);
        }

        // Si la tabla tiene user_id, obtenemos los nombres de usuario
        $userNames = [];
        if (in_array('user_id', $this->columns)) {
            $userIds = $tableData->pluck('user_id')->unique()->filter();
            $userNames = DB::table('users')->whereIn('id', $userIds)->pluck('usuario', 'id');
        }

        return view('livewire.tables', [
            'tableData' => $tableData,
            'columns' => $this->columns,
            'userNames' => $userNames,
        ]);
    }

    public function search()
    {
        $this->resetPage();
    }
}

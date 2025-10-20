<?php

namespace App\Livewire\Mesas;

use App\Models\Table;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;

#[Title('Cabañas')]
class MesasComponent extends Component
{
    use WithPagination;

    public $qrUrl = null;

    // Propiedades clase
    public $mesasCount = 0;
    public $search = '';
    public $pagination = 10;

    // Propiedades modelo
    public $Id;

    public function mount()
    {
        $this->mesasCount();
    }

    public function mesasCount()
    {
        $this->mesasCount = Table::count();
    }

    #[On('createNewTable')]
    public function createNewTable()
    {
        Gate::authorize('crear-mesa');
        Table::create([
            'status' => 'closed',
        ]);

        $this->mesasCount();
    }

    public function openTable(Table $table)
    {
        Gate::authorize('abrir-mesa');
        $table->status = 'open';
        $table->token = Str::uuid();
        $table->save();

        $this->qrUrl = route('mesa.cliente', $table->token);

        // $this->goToTable($table);
    }

    public function closeTable(Table $table)
    {
        Gate::authorize('cerrar-mesa');
        $table->status = 'closed';
        $table->token->delete();
        $table->save();
    }

    public function goToTable(Table $table)
    {
        return redirect()->route('mesas.show', ['table' => $table]);
    }

    #[On('deleteTable')]
    public function destroy($id)
    {
        Gate::authorize('eliminar-mesa');
        $table = Table::findOrFail($id);
        $table->delete();
        $this->mesasCount();
    }

    public function render()
    {
        Gate::authorize('ver-mesa');
        if ($this->search != '') {
            $this->resetPage();
        }

        $mesas = Table::where('code', 'LIKE', '%' . $this->search . '%')
            ->orderBy('code', 'asc')
            ->paginate($this->pagination);

        return view('livewire.mesas.mesas-component', compact('mesas'));
    }
}

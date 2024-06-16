<?php

namespace App\Livewire\Mesas;

use App\Models\Table;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Mesas')]
class MesasComponent extends Component
{
    use WithPagination;

    // Propiedades clase
    public $mesasCount = 0;
    public $search = '';
    public $pagination = 10;

    // Propiedades modelo
    public $Id;

    public function mount(){
        $this->mesasCount();
    }

    public function mesasCount()
    {
        $this->mesasCount = Table::count();
    }

    #[On('createNewTable')]
    public function createNewTable(){
        Table::create([
            'status' => 'closed',
        ]);

        $this->mesasCount();
    }

    public function openTable(Table $table)
    {
        $table->status = 'open';
        $table->save();

        return redirect()->route('mesas.show',  ['table' => $table]);
    }

    public function closeTable(Table $table)
    {
        $table->status = 'closed';
        $table->save();
    }

    public function goToTable(Table $table)
    {
        return redirect()->route('mesas.show', ['table' => $table]);
    }

    #[On('deleteTable')]
    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        $this->mesasCount();
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        $mesas = Table::where('code', 'LIKE', '%' . $this->search . '%')
            ->orderBy('code', 'asc')
            ->paginate($this->pagination);

        return view('livewire.mesas.mesas-component', compact('mesas'));
    }
}

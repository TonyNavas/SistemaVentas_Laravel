<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Title('Roles')]
class RoleComponent extends Component
{
    public $Id = 0;
    public $rolesCount = 0;
    public $pagination = 5;
    public $search;

    // Atributos de clase
    public $name;
    public array $permissions = [];


    public function mount()
    {
        $this->rolesCount();
    }

    public function rolesCount()
    {
        $this->rolesCount = Role::count();
    }

    // Abrir modal
    public function create()
    {
        $this->Id = 0;
        $this->dispatch('open-modal', 'modalRole');
    }

    // Guardar rl
    public function store()
    {
        $this->validate([
            'name' => 'required|min:5|max:255|unique:roles',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create(['name' => $this->name]);

        if (!empty($this->permissions)) {
            $role->permissions()->sync($this->permissions);
        }

        $this->rolesCount();
        $this->reset(['name', 'permissions']);
        $this->dispatch('close-modal', 'modalRole');
        $this->dispatch('msg', 'Rol creado correctamente!');
    }

    // Cargar roles en el modal
    public function edit(Role $role)
    {
        $this->Id = $role->id;
        $this->name = $role->name;

        // Convertir permisos a array de IDs
        $this->permissions = $role->permissions->pluck('id')->toArray();
        $this->dispatch('open-modal', 'modalRole');
    }

    // Actualizar rol
    public function update(Role $role)
    {

        $this->validate([
            'name' => 'required|min:5|max:255|unique:roles,id,' . $this->Id,
        ]);

        $role->update(['name' => $this->name]);

        if (!empty($this->permissions)) {
            $role->permissions()->sync($this->permissions);
        }else{
            $role->permissions()->detach();
        }

        $this->reset(['name', 'permissions']);
        $this->dispatch('close-modal', 'modalRole');
        $this->dispatch('msg', 'Rol actualizado correctamente!');
    }

    // Eliminar Rol
    #[On('destroyRol')]
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        $this->rolesCount();
        $this->dispatch('msg', 'Producto eliminado correctamente!');
    }

    #[Computed()]
    public function roles()
    {
        return Role::query()
            ->withCount('users')
            ->with('permissions')
            ->where('name', 'LIKE', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->pagination);
    }


    public function render()
    {
        $Allpermissions = Permission::all();

        return view('livewire.admin.role-component', [
            'roles' => $this->roles,
            'Allpermissions' => $Allpermissions
        ]);
    }
}

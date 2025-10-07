<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

#[Title('Usuarios')]
class UserComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Propiedades clase
    public $usersCount = 0,  $search = '';
    public $pagination = 5;

    // Propiedades model

    public $Id = 0;
    public $name;
    public $email;
    public $password;
    public $re_password;
    public $admin = true;
    public $active = true;
    public $image;
    public $imageModel;

    public $role;

    public function mount()
    {
        $this->usersCount();
    }

    public function usersCount()
    {
        $this->usersCount = User::count();
    }

    public function create()
    {
        $this->Id = 0;
        $this->resetUI();
        $this->resetValidation();
        $this->dispatch('open-modal', 'modalUser');
    }

    // Crear Usuario
    public function store()
    {
        $this->validate([
            'name' => 'required|min:5|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:5',
            're_password' => 'required|same:password',
            'image' => 'image|max:1024|nullable',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'active' => $this->active,
        ]);
        $user->assignRole($this->role);

        if ($this->image) {
            $customName = 'users/' . uniqid() . '.' . $this->image->extension();
            $this->image->storeAs('public', $customName);
            $user->image()->create(['url' => $customName]);
        }

        $this->usersCount();
        $this->dispatch('close-modal', 'modalUser');
        $this->dispatch('msg', 'Usuario creado correctamente!');
        $this->resetUI();
    }

    public function edit(User $user)
    {

        $this->resetUI();

        $this->Id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->active = $user->active ? true : false;
        $this->imageModel = $user->image ? $user->image->url : null;

        $this->role = $user->roles->pluck('name')->first();

        $this->dispatch('open-modal', 'modalUser');
    }

    public function update(User $user)
    {
        $this->validate([
            'name' => 'required|min:5|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->Id,
            'password' => 'nullable|min:5',
            're_password' => 'same:password',
            'image' => 'image|max:1024|nullable',
            'role' => 'required|string', // ⚡ que el rol sea obligatorio
        ]);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->active = $this->active;

        // Asignar rol
        if ($this->role) {
            $user->syncRoles([$this->role]);
        }

        // Actualizar password si fue editada
        if ($this->password) {
            $user->password = bcrypt($this->password);
        }

        // Guardar cambios
        $user->save();

        // Manejo de imagen
        if ($this->image) {
            if ($user->image != null) {
                Storage::delete('public/' . $user->image->url);
                $user->image()->delete();
            }
            $customName = 'users/' . uniqid() . '.' . $this->image->extension();
            $this->image->storeAs('public', $customName);
            $user->image()->create(['url' => $customName]);
        }

        $this->dispatch('close-modal', 'modalUser');
        $this->dispatch('msg', 'Usuario actualizado correctamente!');
        $this->resetUI();
    }


    #[On('destroyUser')]
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 🔹 Primero limpiar roles y permisos
        $user->roles()->detach();
        $user->permissions()->detach();

        // 🔹 Luego manejar la imagen
        if ($user->image != null) {
            Storage::delete('public/' . $user->image->url);
            $user->image()->delete();
        }

        // 🔹 Finalmente eliminar usuario
        $user->delete();

        $this->usersCount();
        $this->dispatch('msg', 'Usuario eliminado correctamente!');
    }


    // Limpieza de todos los campos
    public function resetUI()
    {
        $this->reset(['Id', 'name', 'email', 'password', 're_password', 'admin', 'active', 'image', 'imageModel']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = User::where('name', 'LIKE', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->pagination);

        $roles = Role::pluck('name', 'name')->all();


        return view('livewire.user.user-component', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
}

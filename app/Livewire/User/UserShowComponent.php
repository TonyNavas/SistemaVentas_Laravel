<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;

#[Title('Ver usuario')]
class UserShowComponent extends Component
{
    use WithPagination;

    public User $user;

    public function render()
    {
        Gate::authorize('ver-usuarios');
        $sales = $this->user->sales()->paginate(5);
        return view('livewire.user.user-show-component', compact('sales'));
    }
}

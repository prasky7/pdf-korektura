<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $editingUserId = null;
    public $selectedRoles = [];
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['sortField', 'sortDirection'];

    // Create user form
    public $showCreateForm = false;
    public $newName = '';
    public $newUsername = '';
    public $newEmail = '';
    public $newPassword = '';
    public $newPasswordConfirmation = '';
    public $newRoles = [];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if (!$this->showCreateForm) {
            $this->resetCreateForm();
        }
    }

    public function createUser()
    {
        $this->validate([
            'newName' => ['required', 'string', 'max:255'],
            'newUsername' => ['required', 'string', 'max:255', 'unique:users,username'],
            'newEmail' => ['required', 'email', 'max:255', 'unique:users,email'],
            'newPassword' => ['required', 'string', 'min:6', 'same:newPasswordConfirmation'],
            'newRoles' => ['required', 'array', 'min:1'],
        ], [
            'newUsername.unique' => 'Toto uživatelské jméno je již obsazeno.',
            'newEmail.unique' => 'Tento email je již obsazen.',
            'newPassword.same' => 'Hesla se neshodují.',
            'newPassword.min' => 'Heslo musí mít alespoň 6 znaků.',
            'newRoles.required' => 'Vyberte alespoň jednu roli.',
            'newRoles.min' => 'Vyberte alespoň jednu roli.',
        ]);

        $user = User::create([
            'name' => $this->newName,
            'username' => $this->newUsername,
            'email' => $this->newEmail,
            'password' => Hash::make($this->newPassword),
        ]);

        $user->syncRoles($this->newRoles);

        $this->resetCreateForm();
        $this->dispatch('notify', type: 'success', message: 'Uživatel byl úspěšně vytvořen.');
    }

    public function resetCreateForm()
    {
        $this->reset(['showCreateForm', 'newName', 'newUsername', 'newEmail', 'newPassword', 'newPasswordConfirmation', 'newRoles']);
        $this->resetValidation();
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    public function saveRoles()
    {
        $user = User::findOrFail($this->editingUserId);
        $user->syncRoles($this->selectedRoles);
        $this->dispatch('notify', type: 'success', message: 'Role byly aktualizovány.');
        $this->reset(['editingUserId', 'selectedRoles']);
    }

    public function cancelEdit()
    {
        $this->reset(['editingUserId', 'selectedRoles']);
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'Nemůžete smazat svůj vlastní účet.');
            return;
        }

        $user->delete();
        $this->dispatch('notify', type: 'success', message: 'Uživatel byl smazán.');
    }

    public function render()
    {
        $query = User::with('roles');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.user-management', [
            'users' => $query->orderBy($this->sortField, $this->sortDirection)->paginate(20),
            'roles' => Role::all(),
        ]);
    }
}

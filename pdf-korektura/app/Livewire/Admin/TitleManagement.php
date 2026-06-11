<?php

namespace App\Livewire\Admin;

use App\Models\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TitleManagement extends Component
{
    use WithPagination;

    public $editingTitleId = null;
    public $name = '';
    public $description = '';
    public $is_active = true;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['sortField', 'sortDirection'];

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:1000'],
        'is_active' => ['boolean'],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->reset(['editingTitleId', 'name', 'description', 'is_active']);
        $this->is_active = true;
    }

    public function edit($titleId)
    {
        $title = Title::findOrFail($titleId);
        $this->editingTitleId = $titleId;
        $this->name = $title->name;
        $this->description = $title->description;
        $this->is_active = $title->is_active;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingTitleId) {
            $title = Title::findOrFail($this->editingTitleId);
            $title->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            $message = 'Titul byl aktualizován.';
        } else {
            Title::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            $message = 'Titul byl vytvořen.';
        }

        $this->dispatch('notify', type: 'success', message: $message);
        $this->reset(['editingTitleId', 'name', 'description', 'is_active']);
    }

    public function cancelEdit()
    {
        $this->reset(['editingTitleId', 'name', 'description', 'is_active']);
    }

    public function delete($titleId)
    {
        $title = Title::findOrFail($titleId);

        if ($title->pdfDocuments()->count() > 0) {
            $this->dispatch('notify', type: 'error', message: 'Nelze smazat titul, který má přiřazená PDF.');
            return;
        }

        $title->delete();
        $this->dispatch('notify', type: 'success', message: 'Titul byl smazán.');
    }

    public function render()
    {
        $query = Title::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return view('livewire.admin.title-management', [
            'titles' => $query->orderBy($this->sortField, $this->sortDirection)->paginate(20),
        ]);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AuditLog extends Component
{
    use WithPagination;

    public $actionFilter = '';
    public $userFilter = '';
    public $titleFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $deadlineFrom = '';
    public $deadlineTo = '';
    public $search = '';
    public $pdfNameSearch = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['actionFilter', 'userFilter', 'titleFilter', 'dateFrom', 'dateTo', 'deadlineFrom', 'deadlineTo', 'search', 'pdfNameSearch', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = ActivityLog::with(['user', 'pdfDocument.title', 'pdfDocument.versions', 'pdfVersion'])
            ->orderBy($this->sortField, $this->sortDirection);

        if ($this->actionFilter) {
            $query->where('action', $this->actionFilter);
        }

        if ($this->userFilter) {
            $query->where('user_id', $this->userFilter);
        }

        if ($this->titleFilter) {
            $query->whereHas('pdfDocument', function ($q) {
                $q->where('title_id', $this->titleFilter);
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if ($this->deadlineFrom) {
            $query->whereHas('pdfDocument', function ($q) {
                $q->where('deadline_date', '>=', $this->deadlineFrom);
            });
        }

        if ($this->deadlineTo) {
            $query->whereHas('pdfDocument', function ($q) {
                $q->where('deadline_date', '<=', $this->deadlineTo . ' 23:59:59');
            });
        }

        if ($this->search) {
            $query->where('details', 'like', '%' . $this->search . '%');
        }

        if ($this->pdfNameSearch) {
            $query->whereHas('pdfDocument', function ($q) {
                $q->where('name', 'like', '%' . $this->pdfNameSearch . '%');
            });
        }

        return view('livewire.admin.audit-log', [
            'logs' => $query->paginate(30),
            'actions' => ActivityLog::select('action')->distinct()->pluck('action'),
            'users' => \App\Models\User::orderBy('name')->get(),
            'titles' => Title::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\PdfDocument;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $titleFilter = '';
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['statusFilter', 'titleFilter', 'search', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function archivePdf($pdfId)
    {
        $pdf = PdfDocument::findOrFail($pdfId);

        if ($pdf->uploaded_by_user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            $this->dispatch('notify', type: 'error', message: 'Nemáte oprávnění k archivaci tohoto PDF.');
            return;
        }

        $pdf->update(['archived_at' => now()]);
        \App\Services\ActivityLogService::log($pdf, \App\Services\ActivityLogService::ACTION_ARCHIVE, 'PDF archivováno');
        $this->dispatch('notify', type: 'success', message: 'PDF bylo archivováno.');
    }

    public function render()
    {
        $user = Auth::user();
        $query = PdfDocument::with(['title', 'assignedTo', 'versions'])
            ->notArchived();

        if ($user->isEditor()) {
            $query->where('uploaded_by_user_id', $user->id);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->titleFilter) {
            $query->where('title_id', $this->titleFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('issue_title', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $pdfs = $query->paginate(15);
        $titles = \App\Models\Title::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total' => PdfDocument::notArchived()->when($user->isEditor(), fn($q) => $q->where('uploaded_by_user_id', $user->id))->count(),
            'uploaded' => PdfDocument::notArchived()->when($user->isEditor(), fn($q) => $q->where('uploaded_by_user_id', $user->id))->where('status', PdfDocument::STATUS_UPLOADED)->count(),
            'in_progress' => PdfDocument::notArchived()->when($user->isEditor(), fn($q) => $q->where('uploaded_by_user_id', $user->id))->where('status', PdfDocument::STATUS_IN_PROGRESS)->count(),
            'completed' => PdfDocument::notArchived()->when($user->isEditor(), fn($q) => $q->where('uploaded_by_user_id', $user->id))->where('status', PdfDocument::STATUS_COMPLETED)->count(),
        ];

        return view('livewire.dashboard', [
            'pdfs' => $pdfs,
            'titles' => $titles,
            'stats' => $stats,
        ]);
    }
}

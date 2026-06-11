<?php

namespace App\Livewire;

use App\Models\PdfDocument;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PdfPool extends Component
{
    use WithPagination;

    public $titleFilter = '';
    public $search = '';
    public $sortField = 'deadline_date';
    public $sortDirection = 'asc';

    protected $queryString = ['titleFilter', 'search', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function assignToMe($pdfId)
    {
        $pdf = PdfDocument::findOrFail($pdfId);

        if ($pdf->isAssigned()) {
            $this->dispatch('notify', type: 'error', message: 'Toto PDF je již přiřazeno jinému korektorovi.');
            return;
        }

        $pdf->update([
            'assigned_to_user_id' => Auth::id(),
            'status' => PdfDocument::STATUS_IN_PROGRESS,
        ]);

        ActivityLogService::log($pdf, ActivityLogService::ACTION_ASSIGN, 'Korektor si přiřadil PDF');

        $this->dispatch('notify', type: 'success', message: 'PDF bylo přiřazeno k Vám.');
    }

    public function render()
    {
        $query = PdfDocument::with(['title', 'uploadedBy'])
            ->unassigned()
            ->notArchived();

        if ($this->titleFilter) {
            $query->where('title_id', $this->titleFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('issue_title', 'like', '%' . $this->search . '%');
            });
        }

        $pdfs = $query->orderBy($this->sortField, $this->sortDirection)->paginate(15);
        $titles = \App\Models\Title::where('is_active', true)->orderBy('name')->get();

        return view('livewire.pdf-pool', [
            'pdfs' => $pdfs,
            'titles' => $titles,
        ]);
    }
}

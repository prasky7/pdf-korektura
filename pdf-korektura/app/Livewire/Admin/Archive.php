<?php

namespace App\Livewire\Admin;

use App\Models\PdfDocument;
use App\Models\Title;
use App\Services\ActivityLogService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Archive extends Component
{
    use WithPagination;

    public $titleFilter = '';
    public $search = '';
    public $sortField = 'archived_at';
    public $sortDirection = 'desc';

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

    public function unarchive($pdfId)
    {
        $pdf = PdfDocument::findOrFail($pdfId);
        $pdf->update(['archived_at' => null]);

        ActivityLogService::log($pdf, ActivityLogService::ACTION_ARCHIVE, 'PDF obnoveno z archivu');

        $this->dispatch('notify', type: 'success', message: 'PDF bylo obnoveno z archivu.');
    }

    public function deleteArchived($pdfId)
    {
        $pdf = PdfDocument::findOrFail($pdfId);

        // Delete all version files
        foreach ($pdf->versions as $version) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($version->file_path);
        }

        ActivityLogService::log($pdf, ActivityLogService::ACTION_ARCHIVE, 'Archivované PDF smazáno: ' . $pdf->name);

        // Delete version records and the document
        $pdf->versions()->delete();
        $pdf->activityLogs()->delete();
        $pdf->delete();

        $this->dispatch('notify', type: 'success', message: 'Archivované PDF bylo smazáno.');
    }

    public function render()
    {
        $query = PdfDocument::with(['title', 'uploadedBy', 'assignedTo'])
            ->archived()
            ->orderBy($this->sortField, $this->sortDirection);

        if ($this->titleFilter) {
            $query->where('title_id', $this->titleFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('issue_title', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.archive', [
            'pdfs' => $query->paginate(20),
            'titles' => Title::orderBy('name')->get(),
            'totalArchived' => PdfDocument::archived()->count(),
        ]);
    }
}

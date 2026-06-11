<?php

namespace App\Livewire;

use App\Models\PdfDocument;
use App\Models\PdfVersion;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MyAssignments extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $uploadingForPdfId = null;
    public $correctedPdf;
    public $originalCorrectedFileName = '';
    public $changeSummary = '';
    public $returnForRevision = false;
    public $sortField = 'deadline_date';
    public $sortDirection = 'asc';

    protected $queryString = ['sortField', 'sortDirection'];

    protected $rules = [
        'correctedPdf' => ['required', 'file', 'mimes:pdf', 'max:51200'],
        'changeSummary' => ['nullable', 'string', 'max:1000'],
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

    public function startUpload($pdfId)
    {
        $this->uploadingForPdfId = $pdfId;
        $this->reset(['correctedPdf', 'changeSummary', 'returnForRevision']);
    }

    public function cancelUpload()
    {
        $this->reset(['uploadingForPdfId', 'correctedPdf', 'changeSummary', 'returnForRevision']);
    }

    public function submitCorrection()
    {
        $this->validate();

        $pdf = PdfDocument::findOrFail($this->uploadingForPdfId);

        if ($pdf->assigned_to_user_id !== Auth::id()) {
            $this->dispatch('notify', type: 'error', message: 'Toto PDF není přiřazeno Vám.');
            return;
        }

        $newVersionNumber = $pdf->current_version_number + 1;
        $title = $pdf->title;
        $folder = 'pdfs/' . $title->name . '/' . now()->format('Y-m') . '/v' . $newVersionNumber;

        // Handle both wire:model (TemporaryUploadedFile) and $wire.upload() (string path)
        if (is_string($this->correctedPdf)) {
            $filePath = $this->correctedPdf;
            $originalName = $this->originalCorrectedFileName ?: basename($filePath);
        } else {
            $originalName = $this->correctedPdf->getClientOriginalName();
            $fileName = uniqid() . '_' . $originalName;
            $filePath = $this->correctedPdf->storeAs($folder, $fileName, 'local');
        }

        $newVersion = PdfVersion::create([
            'pdf_document_id' => $pdf->id,
            'version_number' => $newVersionNumber,
            'file_path' => $filePath,
            'original_filename' => $originalName,
            'uploaded_by_user_id' => Auth::id(),
            'change_summary' => $this->changeSummary ?: 'Korekce provedena',
        ]);

        $pdf->update([
            'current_version_number' => $newVersionNumber,
            'status' => $this->returnForRevision ? PdfDocument::STATUS_RETURNED : PdfDocument::STATUS_COMPLETED,
            'assigned_to_user_id' => $this->returnForRevision ? null : $pdf->assigned_to_user_id,
        ]);

        ActivityLogService::log(
            $pdf,
            ActivityLogService::ACTION_CORRECT,
            'Korektor nahrál opravenou verzi ' . $newVersionNumber,
            $newVersion
        );

        $this->reset(['uploadingForPdfId', 'correctedPdf', 'originalCorrectedFileName', 'changeSummary', 'returnForRevision']);
        $this->dispatch('reset-correction-dropzone');
        $this->dispatch('notify', type: 'success', message: 'Opravené PDF bylo úspěšně nahráno.');
    }

    public function releasePdf($pdfId)
    {
        $pdf = PdfDocument::findOrFail($pdfId);

        if ($pdf->assigned_to_user_id !== Auth::id()) {
            $this->dispatch('notify', type: 'error', message: 'Toto PDF není přiřazeno Vám.');
            return;
        }

        $pdf->update([
            'assigned_to_user_id' => null,
            'status' => PdfDocument::STATUS_UPLOADED,
        ]);

        ActivityLogService::log($pdf, ActivityLogService::ACTION_RELEASE, 'Korektor uvolnil PDF zpět mezi nepřiřazené');

        $this->dispatch('notify', type: 'success', message: 'PDF bylo uvolněno zpět mezi nepřiřazené.');
    }

    public function render()
    {
        $pdfs = PdfDocument::with(['title', 'uploadedBy', 'versions'])
            ->assignedTo(Auth::id())
            ->notArchived()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.my-assignments', [
            'pdfs' => $pdfs,
        ]);
    }
}

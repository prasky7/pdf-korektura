<?php

namespace App\Livewire;

use App\Models\PdfDocument;
use App\Models\PdfVersion;
use App\Models\Title;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class PdfUpload extends Component
{
    use WithFileUploads;

    public $pdfFile;
    public $originalPdfFileName = '';
    public $title_id = '';
    public $name = '';
    public $page_number = '';
    public $issue_title = '';
    public $deadline_date = '';
    public $deadline_time = '';
    public $assigned_to_user_id = '';

    public function mount()
    {
        $this->deadline_date = now()->format('Y-m-d');
        $this->deadline_time = now()->format('H:i');
    }

    protected $rules = [
        'pdfFile' => ['required', 'file', 'mimes:pdf', 'max:51200'],
        'title_id' => ['required', 'exists:titles,id'],
        'name' => ['nullable', 'string', 'max:255'],
        'page_number' => ['nullable', 'integer', 'min:1'],
        'issue_title' => ['nullable', 'string', 'max:255'],
        'deadline_date' => ['required', 'date'],
        'deadline_time' => ['required', 'date_format:H:i'],
        'assigned_to_user_id' => ['nullable', 'exists:users,id'],
    ];

    /**
     * Handle file uploaded via drag-and-drop (Alpine.js $wire.upload).
     * Livewire stores the file in a temp location and returns a temp path string.
     */
    public function updatedPdfFile()
    {
        // This is called after wire:model or $wire.upload() finishes.
        // Dispatch event so Alpine resets the fileName display.
        $this->dispatch('file-uploaded');
    }

    public function save()
    {
        $this->validate();

        $title = Title::find($this->title_id);
        $folder = 'pdfs/' . $title->name . '/' . now()->format('Y-m') . '/original';

        // Handle both wire:model (TemporaryUploadedFile) and $wire.upload() (string path)
        if (is_string($this->pdfFile)) {
            // Already stored by $wire.upload() – use the temp path directly
            $filePath = $this->pdfFile;
            $originalName = $this->originalPdfFileName ?: basename($filePath);
        } else {
            $originalName = $this->pdfFile->getClientOriginalName();
            $fileName = uniqid() . '_' . $originalName;
            $filePath = $this->pdfFile->storeAs($folder, $fileName, 'local');
        }

        $deadlineDateTime = $this->deadline_date . ' ' . $this->deadline_time;

        $assignedUserId = $this->assigned_to_user_id ?: null;
        $status = $assignedUserId ? PdfDocument::STATUS_IN_PROGRESS : PdfDocument::STATUS_UPLOADED;

        $pdfDocument = PdfDocument::create([
            'title_id' => $this->title_id,
            'uploaded_by_user_id' => Auth::id(),
            'name' => $this->name,
            'page_number' => $this->page_number,
            'issue_title' => $this->issue_title,
            'deadline_date' => $deadlineDateTime,
            'status' => $status,
            'current_version_number' => 1,
            'assigned_to_user_id' => $assignedUserId,
        ]);

        $pdfVersion = PdfVersion::create([
            'pdf_document_id' => $pdfDocument->id,
            'version_number' => 1,
            'file_path' => $filePath,
            'original_filename' => $originalName,
            'uploaded_by_user_id' => Auth::id(),
            'change_summary' => 'První verze - nahrání editorovi',
        ]);

        ActivityLogService::log($pdfDocument, ActivityLogService::ACTION_UPLOAD, 'PDF nahráno do systému', $pdfVersion);

        if ($assignedUserId) {
            ActivityLogService::log($pdfDocument, ActivityLogService::ACTION_ASSIGN, 'PDF přiřazeno korektorovi při nahrání', $pdfVersion);
        }

        $this->reset(['pdfFile', 'originalPdfFileName', 'title_id', 'name', 'page_number', 'issue_title', 'deadline_date', 'deadline_time', 'assigned_to_user_id']);
        $this->deadline_date = now()->format('Y-m-d');
        $this->deadline_time = now()->format('H:i');
        $this->dispatch('reset-dropzone');
        $this->dispatch('notify', type: 'success', message: 'PDF bylo úspěšně nahráno.');
    }

    public function render()
    {
        return view('livewire.pdf-upload', [
            'titles' => Title::where('is_active', true)->orderBy('name')->get(),
            'proofreaders' => User::role('Korektor')->orderBy('name')->get(),
        ]);
    }
}

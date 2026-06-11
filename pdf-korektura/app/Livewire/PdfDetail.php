<?php

namespace App\Livewire;

use App\Models\PdfDocument;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PdfDetail extends Component
{
    public PdfDocument $pdfDocument;

    public function mount(PdfDocument $pdfDocument)
    {
        $this->pdfDocument = $pdfDocument->load(['title', 'uploadedBy', 'assignedTo', 'versions.uploadedBy', 'activityLogs.user']);
    }

    public function render()
    {
        return view('livewire.pdf-detail');
    }
}

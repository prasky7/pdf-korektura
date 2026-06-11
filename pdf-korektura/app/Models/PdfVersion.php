<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdfVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'pdf_document_id',
        'version_number',
        'file_path',
        'original_filename',
        'uploaded_by_user_id',
        'change_summary',
    ];

    protected function casts(): array
    {
        return [
            'version_number' => 'integer',
        ];
    }

    public function pdfDocument(): BelongsTo
    {
        return $this->belongsTo(PdfDocument::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function getFileUrl(): string
    {
        return route('pdf.download', ['pdfDocument' => $this->pdf_document_id, 'version' => $this->version_number]);
    }
}

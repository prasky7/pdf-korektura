<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\PdfDocument;
use App\Models\PdfVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public const ACTION_UPLOAD = 'upload';
    public const ACTION_ASSIGN = 'assign';
    public const ACTION_RELEASE = 'release';
    public const ACTION_CORRECT = 'correct';
    public const ACTION_ARCHIVE = 'archive';
    public const ACTION_VIEW = 'view';
    public const ACTION_DOWNLOAD = 'download';

    public static function log(?PdfDocument $pdfDocument, string $action, ?string $details = null, ?PdfVersion $pdfVersion = null): void
    {
        ActivityLog::create([
            'pdf_document_id' => $pdfDocument?->id,
            'pdf_version_id' => $pdfVersion?->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => $details,
            'ip_address' => Request::ip(),
        ]);
    }
}

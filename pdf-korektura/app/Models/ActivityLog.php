<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    public const ACTION_UPLOAD = 'upload';
    public const ACTION_ASSIGN = 'assign';
    public const ACTION_RELEASE = 'release';
    public const ACTION_CORRECT = 'correct';
    public const ACTION_ARCHIVE = 'archive';
    public const ACTION_VIEW = 'view';
    public const ACTION_DOWNLOAD = 'download';

    protected $fillable = [
        'pdf_document_id',
        'pdf_version_id',
        'user_id',
        'action',
        'details',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function pdfDocument(): BelongsTo
    {
        return $this->belongsTo(PdfDocument::class);
    }

    public function pdfVersion(): BelongsTo
    {
        return $this->belongsTo(PdfVersion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            self::ACTION_UPLOAD => 'Nahrání',
            self::ACTION_ASSIGN => 'Přiřazení',
            self::ACTION_RELEASE => 'Uvolnění',
            self::ACTION_CORRECT => 'Korekce',
            self::ACTION_ARCHIVE => 'Archivace',
            self::ACTION_VIEW => 'Zobrazení',
            self::ACTION_DOWNLOAD => 'Stažení',
            default => $this->action,
        };
    }
}

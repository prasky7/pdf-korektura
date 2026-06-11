<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PdfDocument extends Model
{
    use HasFactory;

    public const STATUS_UPLOADED = 'uploaded';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'title_id',
        'uploaded_by_user_id',
        'name',
        'page_number',
        'issue_title',
        'deadline_date',
        'status',
        'assigned_to_user_id',
        'current_version_number',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline_date' => 'datetime',
            'archived_at' => 'datetime',
            'current_version_number' => 'integer',
        ];
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PdfVersion::class)->orderBy('version_number', 'desc');
    }

    public function latestVersion(): BelongsTo
    {
        return $this->belongsTo(PdfVersion::class, 'id', 'pdf_document_id')
            ->whereColumn('pdf_versions.version_number', 'pdf_documents.current_version_number');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    public function scopeForEditor($query, int $userId)
    {
        return $query->where('uploaded_by_user_id', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to_user_id')
            ->where('status', self::STATUS_UPLOADED);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to_user_id', $userId);
    }

    public function scopeNotArchived($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function isAssigned(): bool
    {
        return !is_null($this->assigned_to_user_id);
    }

    public function isArchived(): bool
    {
        return !is_null($this->archived_at);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_UPLOADED => 'Vloženo',
            self::STATUS_IN_PROGRESS => 'V procesu',
            self::STATUS_RETURNED => 'Vráceno zpět',
            self::STATUS_COMPLETED => 'Hotovo',
            default => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_UPLOADED => 'gray',
            self::STATUS_IN_PROGRESS => 'blue',
            self::STATUS_RETURNED => 'yellow',
            self::STATUS_COMPLETED => 'green',
            default => 'gray',
        };
    }
}

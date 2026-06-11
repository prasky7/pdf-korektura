<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'guid',
        'domain',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function uploadedPdfs()
    {
        return $this->hasMany(PdfDocument::class, 'uploaded_by_user_id');
    }

    public function assignedPdfs()
    {
        return $this->hasMany(PdfDocument::class, 'assigned_to_user_id');
    }

    public function pdfVersions()
    {
        return $this->hasMany(PdfVersion::class, 'uploaded_by_user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    public function isEditor(): bool
    {
        return $this->hasRole('Editor') || $this->hasRole('Grafik');
    }

    public function isGrafik(): bool
    {
        return $this->hasRole('Grafik');
    }

    public function isProofreader(): bool
    {
        return $this->hasRole('Korektor');
    }
}

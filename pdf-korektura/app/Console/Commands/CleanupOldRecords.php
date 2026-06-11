<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\PdfDocument;
use App\Models\PdfVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOldRecords extends Command
{
    protected $signature = 'app:cleanup-old-records {--days=60 : Number of days to retain records}';
    protected $description = 'Clean up PDF documents, versions and activity logs older than specified days';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Cleaning up records older than {$days} days (before {$cutoffDate->toDateTimeString()})...");

        $oldPdfs = PdfDocument::where('created_at', '<', $cutoffDate)->whereNotNull('archived_at')->get();
        $pdfCount = 0;
        $versionCount = 0;

        foreach ($oldPdfs as $pdf) {
            foreach ($pdf->versions as $version) {
                if (Storage::exists($version->file_path)) {
                    Storage::delete($version->file_path);
                }
                $versionCount++;
            }
            $pdf->versions()->delete();
            $pdf->activityLogs()->delete();
            $pdf->delete();
            $pdfCount++;
        }

        $logCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Deleted {$pdfCount} PDF documents, {$versionCount} versions, and {$logCount} activity logs.");

        return self::SUCCESS;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PdfDocument;
use App\Models\PdfVersion;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfDocumentController extends Controller
{
    public function download(Request $request, PdfDocument $pdfDocument, ?int $version = null)
    {
        $user = Auth::user();

        if (!$this->canAccess($user, $pdfDocument)) {
            abort(403, 'Nemáte oprávnění k přístupu k tomuto souboru.');
        }

        $versionModel = $version
            ? $pdfDocument->versions()->where('version_number', $version)->firstOrFail()
            : $pdfDocument->versions()->latest('version_number')->firstOrFail();

        ActivityLogService::log(
            $pdfDocument,
            ActivityLogService::ACTION_DOWNLOAD,
            "Stažení verze {$versionModel->version_number}",
            $versionModel
        );

        $path = storage_path('app/' . $versionModel->file_path);

        if (!file_exists($path)) {
            abort(404, 'Soubor nebyl nalezen.');
        }

        $downloadName = $this->buildDownloadFilename($versionModel);

        return response()->download($path, $downloadName);
    }

    public function preview(Request $request, PdfDocument $pdfDocument)
    {
        $user = Auth::user();

        if (!$this->canAccess($user, $pdfDocument)) {
            abort(403, 'Nemáte oprávnění k přístupu k tomuto souboru.');
        }

        $versionModel = $pdfDocument->versions()->latest('version_number')->firstOrFail();
        $path = storage_path('app/' . $versionModel->file_path);

        if (!file_exists($path)) {
            abort(404, 'Soubor nebyl nalezen.');
        }

        ActivityLogService::log($pdfDocument, ActivityLogService::ACTION_VIEW, 'Náhled PDF', $versionModel);

        $downloadName = $this->buildDownloadFilename($versionModel);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    private function canAccess($user, PdfDocument $pdfDocument): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (($user->isEditor() || $user->isGrafik()) && $pdfDocument->uploaded_by_user_id === $user->id) {
            return true;
        }

        if ($user->isProofreader() && $pdfDocument->assigned_to_user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Build download filename from original filename + version.
     * Example: 001a.pdf + version 3 => 001a_v3.pdf
     */
    private function buildDownloadFilename(PdfVersion $version): string
    {
        $original = $version->original_filename ?: basename($version->file_path);
        $ext = pathinfo($original, PATHINFO_EXTENSION);
        $base = pathinfo($original, PATHINFO_FILENAME);
        return $base . '_v' . $version->version_number . '.' . $ext;
    }

    /**
     * Silent download — no audit log entry.
     */
    public function downloadSilent(Request $request, PdfDocument $pdfDocument, ?int $version = null)
    {
        $user = Auth::user();

        if (!$this->canAccess($user, $pdfDocument)) {
            abort(403, 'Nemáte oprávnění k přístupu k tomuto souboru.');
        }

        $versionModel = $version
            ? $pdfDocument->versions()->where('version_number', $version)->firstOrFail()
            : $pdfDocument->versions()->latest('version_number')->firstOrFail();

        $path = storage_path('app/' . $versionModel->file_path);

        if (!file_exists($path)) {
            abort(404, 'Soubor nebyl nalezen.');
        }

        $downloadName = $this->buildDownloadFilename($versionModel);

        return response()->download($path, $downloadName);
    }

    /**
     * Silent preview — no audit log entry.
     */
    public function previewSilent(Request $request, PdfDocument $pdfDocument)
    {
        $user = Auth::user();

        if (!$this->canAccess($user, $pdfDocument)) {
            abort(403, 'Nemáte oprávnění k přístupu k tomuto souboru.');
        }

        $versionModel = $pdfDocument->versions()->latest('version_number')->firstOrFail();
        $path = storage_path('app/' . $versionModel->file_path);

        if (!file_exists($path)) {
            abort(404, 'Soubor nebyl nalezen.');
        }

        $downloadName = $this->buildDownloadFilename($versionModel);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PdfDocument;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function releasePdf(Request $request, PdfDocument $pdfDocument)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        if (!$pdfDocument->isAssigned()) {
            return back()->with('error', 'PDF není přiřazeno žádnému korektorovi.');
        }

        $previousAssignee = $pdfDocument->assignedTo?->name ?? 'Neznámý';

        $pdfDocument->update([
            'assigned_to_user_id' => null,
            'status' => PdfDocument::STATUS_UPLOADED,
        ]);

        ActivityLogService::log(
            $pdfDocument,
            ActivityLogService::ACTION_RELEASE,
            "Admin uvolnil PDF (předchozí: {$previousAssignee}). Důvod: " . ($request->input('reason') ?: ' neuveden')
        );

        return back()->with('success', 'PDF bylo úspěšně uvolněno.');
    }

    public function reassignPdf(Request $request, PdfDocument $pdfDocument)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $newUser = User::findOrFail($request->input('user_id'));

        $pdfDocument->update([
            'assigned_to_user_id' => $newUser->id,
            'status' => PdfDocument::STATUS_IN_PROGRESS,
        ]);

        ActivityLogService::log(
            $pdfDocument,
            ActivityLogService::ACTION_ASSIGN,
            "Admin přeřadil PDF k uživateli {$newUser->name}. Důvod: " . ($request->input('reason') ?: ' neuveden')
        );

        return back()->with('success', "PDF bylo přeřazeno uživateli {$newUser->name}.");
    }
}

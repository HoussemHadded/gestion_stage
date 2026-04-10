<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Offre;
use App\Models\Candidature;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export student's own candidature to PDF.
     */
    public function exportStudentCandidature($id)
    {
        $candidature = Candidature::with(['offre.entreprise', 'student'])->findOrFail($id);
        
        // Authorization check: Only the student who owns it, or an admin, or the company can download it.
        // For simplicity, using simple auth check as required by PFE bounds
        if (auth()->user()->isStudent() && auth()->id() !== $candidature->student_id) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('pdf.candidature', compact('candidature'));
        return $pdf->download('candidature_' . $candidature->id . '.pdf');
    }

    /**
     * Export absolute list of all users
     */
    public function exportAdminUsers()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $users = User::all();
        $pdf = Pdf::loadView('pdf.admin_users', compact('users'));
        return $pdf->download('rapport_utilisateurs.pdf');
    }

    /**
     * Export absolute list of all offers
     */
    public function exportAdminOffers()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $offres = Offre::with('entreprise')->withCount('candidatures')->get();
        $pdf = Pdf::loadView('pdf.admin_offres', compact('offres'));
        return $pdf->download('rapport_offres.pdf');
    }

    /**
     * Export absolute list of all applications
     */
    public function exportAdminCandidatures()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $candidatures = Candidature::with(['student', 'offre.entreprise'])->get();
        $pdf = Pdf::loadView('pdf.admin_candidatures', compact('candidatures'));
        return $pdf->download('rapport_candidatures.pdf');
    }
}

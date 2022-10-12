<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function biodataPdf(User $user, Student $santri)
    {
        $santri['family'] = $santri->family;
        $pdf = Pdf::loadView('pdf.biodata', compact('santri'));
        if ($user->isManager()) {
            return $pdf->stream();
        } else {
            return $pdf->download('bio-' . $santri->nama . '.pdf');
        }
    }
    public function mouPdf(User $user, Student $santri)
    {
        $santri['family'] = $santri->family;
        $pdf = Pdf::loadView('pdf.mou', compact('santri'));
        if ($user->isManager()) {
            return $pdf->stream();
        } else {
            return $pdf->download('mou-' . $santri->nama . '.pdf');
        }
    }
}

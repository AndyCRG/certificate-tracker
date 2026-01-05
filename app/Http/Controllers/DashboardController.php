<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Month & Year filters
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];
        $currentMonth = $request->get('month', Carbon::now()->format('F'));
        $currentYear = $request->get('year', Carbon::now()->year);
        $years = range(Carbon::now()->year - 5, Carbon::now()->year + 1);

        // Dashboard stats
        $totalCertificates = Certificate::count();

        // Certificates that are approved
        $approvedCount = Certificate::whereNotNull('certificate_file')->count();

        // Certificates collected (you can adjust if different logic)
        $receivedCertificates = $approvedCount;

        // Pending = total - approved/received
        $pendingCertificates = $totalCertificates - $receivedCertificates;

        // Total distinct participants
        $totalParticipants = DB::table('certificate_participant')
            ->distinct('participant_email')
            ->count('participant_email');
        // For compatibility with your Blade
        $participantCount = $totalParticipants;

        // Pre-calculate remaining participants for JS
        $remainingParticipants = max($totalCertificates - $participantCount, 0);

        return view('dashboard', compact(
            'months',
            'years',
            'currentMonth',
            'currentYear',
            'totalCertificates',
            'approvedCount',
            'receivedCertificates',
            'pendingCertificates',
            'totalParticipants',
            'participantCount',
            'remainingParticipants'
        ));
    }
}

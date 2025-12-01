<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Participant;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantsImport;
use Illuminate\Support\Facades\DB;


class CertificateController extends Controller
{

    public function dashboard()
    {
        // Total certificates in DB
        $totalCertificates = Certificate::count();

        // Certificates that HAVE files (collected)
        $receivedCertificates = Certificate::whereNotNull('certificate_file')->count();

        // Pending = total minus collected
        $pendingCertificates = $totalCertificates - $receivedCertificates;

        // Total participants in DB
        $totalParticipants = Participant::count();

        return view('dashboard', compact(
            'totalCertificates',
            'receivedCertificates',
            'pendingCertificates',
            'totalParticipants'
        ));
    }


    /* --------------------------------------
 | CERTIFICATES CRUD
 --------------------------------------- */
    public function index()
    {
        // Eager load participants to avoid N+1 queries
        $certificates = Certificate::with('participants')->get();

        // Add counts
        $certificates->each(function ($certificate) {
            $participants = $certificate->participants;

            $certificate->participants_count = $participants->count();
            $certificate->collected_count = $participants->where('pivot.collected', 1)->count();
            $certificate->not_collected_count = $certificate->participants_count - $certificate->collected_count;
        });

        return view('certificates.index', compact('certificates'));
    }


    public function create()
    {
        return view('certificates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'        => 'required|string|max:255',
            'course_name'      => 'required|string|max:255',
            'certificate_name' => 'required|string|max:255',
            'issued_by'        => 'required|string|max:255',
            'issue_date'       => 'required|date',
            'expiry_date'      => 'nullable|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $path = $request->hasFile('certificate_file')
            ? $request->file('certificate_file')->store('certificates', 'public')
            : null;

        // 1️⃣ Create the certificate
        $certificate = Certificate::create([
            'course_id'        => $request->course_id,
            'course_name'      => $request->course_name,
            'certificate_name' => $request->certificate_name,
            'issued_by'        => $request->issued_by,
            'issue_date'       => $request->issue_date,
            'expiry_date'      => $request->expiry_date,
            'certificate_file' => $path,
        ]);

        // 2️⃣ Attach participants enrolled in this course
        $participantEmails = Participant::where('course_id', $request->course_id)
            ->pluck('email')
            ->toArray();

        $certificate->participants()->attach($participantEmails, ['collected' => 0]);

        return redirect()->route('certificates.index')
            ->with('success', 'Certificate added successfully!');
    }

    public function edit(Certificate $certificate)
    {
        return view('certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'course_id'        => 'required|string|max:255',
            'course_name'      => 'required|string|max:255',
            'issued_by'        => 'required|string|max:255',
            'issue_date'       => 'required|date',
            'expiry_date'      => 'nullable|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $path = $certificate->certificate_file;
        if ($request->hasFile('certificate_file')) {
            $path = $request->file('certificate_file')->store('certificates', 'public');
        }

        $certificate->update([
            'course_id'        => $request->course_id,
            'course_name'      => $request->course_name,
            'issued_by'        => $request->issued_by,
            'issue_date'       => $request->issue_date,
            'expiry_date'      => $request->expiry_date,
            'certificate_file' => $path,
        ]);

        return redirect()->route('certificates.index')->with('success', 'Certificate updated successfully!');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('certificates.index')->with('success', 'Certificate deleted successfully!');
    }


    /* --------------------------------------
 | PARTICIPANTS LIST + SEARCH
 --------------------------------------- */
    public function participantsIndex(Request $request)
    {
        $search = $request->input('search');

        $participants = Participant::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            })
            ->orderBy('name')
            ->get()
            ->map(function ($p) {

                // Fetch certificates via pivot table
                $certificates = \Illuminate\Support\Facades\DB::table('certificate_participant')
                    ->join('certificates', 'certificate_participant.certificate_id', '=', 'certificates.id')
                    ->where('certificate_participant.participant_email', $p->email)
                    ->select('certificates.*')
                    ->get();

                // Count distinct courses
                $p->totalCourses = $certificates->pluck('course_id')->unique()->count();

                // Count collected / not collected
                $p->collected = $certificates->where('collected', 1)->count();
                $p->notCollected = $certificates->where('collected', 0)->count();

                return $p;
            });

        return view('participants.index', compact('participants', 'search'));
    }

    /* --------------------------------------
 | PARTICIPANT CERTIFICATES PAGE
 --------------------------------------- */
    public function participantsShow($email)
    {
        $participant = Participant::where('email', $email)->firstOrFail();

        // Fetch all certificates linked via the pivot table
        $certificates = DB::table('certificate_participant')
            ->join('certificates', 'certificate_participant.certificate_id', '=', 'certificates.id')
            ->where('certificate_participant.participant_email', $participant->email)
            ->select('certificates.*')
            ->get();

        // Count distinct courses
        $totalCourses = $certificates->pluck('course_id')->unique()->count();

        // Separate collected / not collected
        $collectedCertificates = $certificates->where('collected', 1);
        $notCollectedCertificates = $certificates->where('collected', 0);

        $totalCollected = $collectedCertificates->count();
        $totalNotCollected = $notCollectedCertificates->count();

        return view('participants.show', compact(
            'participant',
            'certificates',
            'totalCourses',
            'collectedCertificates',
            'notCollectedCertificates',
            'totalCollected',
            'totalNotCollected'
        ));
    }


    /* --------------------------------------
 | TOGGLE COLLECTED STATUS
 --------------------------------------- */
    public function toggleCollected($certificateId, Request $request)
    {
        $participantEmail = $request->input('participant_email');
        $collectedBy = $request->input('collected_by', 'Manual');

        if (!$participantEmail) {
            return response()->json(['success' => false, 'message' => 'Participant email missing.'], 400);
        }

        $record = DB::table('certificate_participant')
            ->where('certificate_id', $certificateId)
            ->where('participant_email', $participantEmail)
            ->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $newCollected = !$record->collected;

        DB::table('certificate_participant')
            ->where('certificate_id', $certificateId)
            ->where('participant_email', $participantEmail)
            ->update([
                'collected' => $newCollected ? 1 : 0,
                'collected_at' => $newCollected ? now() : null,
                'collected_by' => $newCollected ? $collectedBy : null
            ]);

        return response()->json([
            'success' => true,
            'collected' => $newCollected,
            'collected_by' => $newCollected ? $collectedBy : null,
            'collected_at' => $newCollected ? now()->format('d-M-Y H:i') : null
        ]);
    }

    /* --------------------------------------
 | ADD SINGLE PARTICIPANT
 --------------------------------------- */
    public function singleparticipant()
    {
        return view('participants.create');
    }

    public function store2(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:participants,email',
            'phone'        => 'required|string|max:20',
            'organization' => 'nullable|string|max:255',
        ]);

        Participant::create($request->all());

        return redirect()->route('participants.index')
            ->with('success', 'Participant added successfully!');
    }


    /* --------------------------------------
 | BULK UPLOAD + UNDO
 --------------------------------------- */
    public function uploadForm()
    {
        return view('participants.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,txt',
        ]);

        $batch_id = "batch_" . time();

        Excel::import(new ParticipantsImport($batch_id), $request->file('file'));

        return redirect()->route('participants.index')
            ->with('success', 'Participants uploaded successfully!')
            ->with('batch_id', $batch_id);
    }


    /* --------------------------------------
 | UNDO LAST UPLOAD
 --------------------------------------- */
    public function undoUpload(Request $request)
    {
        $request->validate([
            'batch_id' => 'required'
        ]);

        Participant::where('batch_id', $request->batch_id)->delete();

        return redirect()->route('participants.index')
            ->with('success', 'Upload undone successfully!');
    }


    /* --------------------------------------
 | PARTICIPANTS DASHBOARD
 --------------------------------------- */
    public function participants_dashboard()
    {
        $totalParticipants = Participant::count();

        return view('participants.index', compact('totalParticipants'));
    }
}

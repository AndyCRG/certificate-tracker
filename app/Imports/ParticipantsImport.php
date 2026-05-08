<?php

namespace App\Imports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\DB;
use App\Models\Certificate;

class ParticipantsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithUpserts
{
    public function headingRow(): int
    {
        return 6;
    }

    public function model(array $row)
    {
        $email = isset($row['email']) ? trim($row['email']) : null;

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        // CLEAN COURSE ID
        $courseId = strtoupper(trim($row['course_id'] ?? ''));

        // CLEAN COURSE NAME
        $courseName = trim($row['course_name'] ?? '');

        $participant = Participant::updateOrCreate(
            ['email' => $email],
            [
                'name' => trim($row['name'] ?? ''),
                'phone' => trim($row['phone_no'] ?? ''),
                'organization' => trim($row['institution'] ?? ''),
                'course_id' => $courseId,
                'course_name' => $courseName,
            ]
        );

        // DEBUGGING
        \Log::info('Participant Course ID: ' . $participant->course_id);

        // FIND CERTIFICATE
        $certificate = Certificate::whereRaw('TRIM(UPPER(course_id)) = ?', [$courseId])->first();

        // DEBUGGING
        \Log::info('Certificate Found: ' . ($certificate ? 'YES' : 'NO'));

        if ($certificate) {

            $exists = DB::table('certificate_participant')
                ->where('certificate_id', $certificate->id)
                ->where('participant_email', $participant->email)
                ->exists();

            if (!$exists) {

                DB::table('certificate_participant')->insert([
                    'certificate_id' => $certificate->id,
                    'participant_email' => $participant->email,
                    'collected' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return $participant;
    }

    public function uniqueBy()
    {
        return 'email';
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}

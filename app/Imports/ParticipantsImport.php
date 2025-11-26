<?php

namespace App\Imports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ParticipantsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithUpserts
{
    public function model(array $row)
    {
        return new Participant([
            'email'        => $row['email'] ?? null,
            'name'         => $row['name'] ?? null,
            'phone'        => $row['phone'] ?? null,
            'organization' => $row['organization'] ?? null,
            'course_id'    => $row['course_id'] ?? null,    // new
            'course_name'  => $row['course_name'] ?? null,  // new
        ]);
    }

    /**
     * Define the unique column to prevent duplicate inserts
     */
    public function uniqueBy()
    {
        return 'email'; // skip duplicates based on email
    }

    /**
     * Process 500 rows at a time
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Insert 500 records at once instead of 1 by 1
     */
    public function batchSize(): int
    {
        return 500;
    }
}

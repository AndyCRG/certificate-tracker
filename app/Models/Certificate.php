<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Certificate extends Model
{
    //
    use HasFactory;

    // Add certificate_name to fillable to allow mass assignment
    protected $fillable = [
        'certificate_name',
        'certificate_file',
        'course_id',       // add this
        'course_name',     // add this
        'user_id',
        'collected',
    ];

    public function participants()
    {
        return $this->belongsToMany(
            Participant::class,
            'certificate_participant',
            'certificate_id',    // FK on pivot table pointing to this model
            'participant_email', // FK on pivot table pointing to Participant
            'id',                // Local PK (Certificate)
            'email'              // Related PK (Participant)
        )->withPivot('collected', 'collected_at');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participants';

    protected $fillable = [
        'email',
        'name',
        'phone',
        'organization',
        'batch_id',
        'course_id',    // add this
        'course_name',  // add this
    ];


    public $timestamps = true;

    public function certificates()
    {
        return $this->belongsToMany(
            Certificate::class,
            'certificate_participant',
            'participant_email', // pivot column for Participant
            'certificate_id',    // pivot column for Certificate
            'email',             // Participant PK
            'id'                 // Certificate PK
        )->withPivot('collected', 'collected_at');
    }

    // Participant.php
    public function courses()
    {
        return $this->belongsToMany(
            Certificate::class,
            'participant_courses',
            'participant_email',
            'course_id',
            'email',
            'course_id'
        );
    }

    
}

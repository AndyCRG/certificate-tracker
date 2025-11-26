<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participants';

    protected $primaryKey = 'email'; // email as primary key

    public $incrementing = false; // PK is string

    protected $keyType = 'string';

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
        return $this->belongsToMany(Certificate::class, 'certificate_participant')
            ->withPivot('collected', 'collected_at');
    }
    // Participant.php
    public function courses()
    {
        return $this->belongsToMany(Certificate::class, 'participant_courses', 'participant_id', 'course_id');
    }
}

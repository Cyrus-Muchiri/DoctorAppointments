<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'preferred_date',
        'status',
        'remarks',
        'reason'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    protected $table        = 'cohorts';
    protected $fillable     = ['school_id', 'name', 'description', 'start_date', 'end_date'];



    // Liaison avec l'école
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Liaison avec les étudiants
    public function students() {
        return $this->belongsToMany(User::class, 'cohort_student', 'cohort_id', 'user_id')->withTimestamps();
    }
}

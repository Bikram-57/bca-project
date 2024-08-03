<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedSubject extends Model
{
    use HasFactory;
    protected $table = 'assigned_subjects';
    protected $fillable = ['cid', 'faculty_id', 'year'];

    public function course(){
        return $this->belongsTo(Courses::class, 'cid', 'cid');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'faculty_id');
    }
}

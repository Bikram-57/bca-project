<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $table = 'support';

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'attachment',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

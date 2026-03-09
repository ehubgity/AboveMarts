<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Smstransaction extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'smstransactions';
    
    protected function user() {
        return $this->belongsTo(User::class,"userId","id");
    }
}

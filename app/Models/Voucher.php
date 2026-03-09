<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'vouchers';
    // protected $casts = [
    //     'lucky_numbers' => 'array',
    //     'lucky_numbers_confirm' => 'array',
    //     'all_numbers' => 'array',
    // ];
    public function user() {
        return $this->belongsTo(User::class);
    }
    
     public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiveAwayContacts extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'give_away_contacts';
}

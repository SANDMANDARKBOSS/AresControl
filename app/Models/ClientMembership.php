<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;
class ClientMembership extends Pivot { protected $table = "client_membership"; protected $fillable = ["client_id", "membership_id"]; }
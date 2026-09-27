<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Plan extends Model { protected $fillable = ["name", "min_capacity", "max_capacity", "price", "validity_days"];
public function memberships() { return $this->hasMany(Membership::class); } }
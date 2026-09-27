<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Membership extends Model { protected $fillable = ["plan_id", "status", "start_date", "end_date", "group_name"];
public function plan() { return $this->belongsTo(Plan::class); }
public function clients() { return $this->belongsToMany(Client::class, "client_membership"); }
public function payments() { return $this->hasMany(Payment::class); } }
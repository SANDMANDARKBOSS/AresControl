<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NutritionalPlan extends Model { protected $fillable = ["client_id", "user_id", "start_date", "end_date", "note", "additional"];
public function client() { return $this->belongsTo(Client::class); }
public function user() { return $this->belongsTo(User::class); }
public function meals() { return $this->hasMany(Meal::class); } }
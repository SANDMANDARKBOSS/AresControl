<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Measurement extends Model { protected $fillable = ["client_id", "user_id", "date", "weight", "fat", "muscle", "chest", "waist", "hip", "left_leg", "right_leg", "left_calf", "right_calf", "back", "left_bicep", "right_bicep", "bmi"];
public function client() { return $this->belongsTo(Client::class); }
public function user() { return $this->belongsTo(User::class); }
public function folds() { return $this->hasMany(Fold::class); } }
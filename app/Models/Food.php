<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Food extends Model { protected $table = "foods"; protected $fillable = ["meal_id", "description"];
public function meal() { return $this->belongsTo(Meal::class); } }
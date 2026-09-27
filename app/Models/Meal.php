<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Meal extends Model { protected $fillable = ["nutritional_plan_id", "type"];
public function nutritionalPlan() { return $this->belongsTo(NutritionalPlan::class); }
public function foods() { return $this->hasMany(Food::class); } }
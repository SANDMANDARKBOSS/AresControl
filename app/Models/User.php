<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable { use Notifiable;
protected $fillable = ["role_id", "name", "last_name", "email", "password", "status"];
protected $hidden = ["password", "remember_token"];
public function role() { return $this->belongsTo(Role::class); }
public function measurements() { return $this->hasMany(Measurement::class); }
public function nutritionalPlans() { return $this->hasMany(NutritionalPlan::class); } }
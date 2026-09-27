<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Client extends Model { 
    protected $fillable = ["user_id", "name", "last_name", "gender", "id_card", "phone", "entry_date", "birth_date", "accepts_measurements"];
    protected $casts = [
        'accepts_measurements' => 'boolean',
    ];
    public function user() { return $this->belongsTo(User::class); }
    public function memberships() { return $this->belongsToMany(Membership::class, "client_membership"); }
    public function measurements() { return $this->hasMany(Measurement::class); }
    public function nutritionalPlans() { return $this->hasMany(NutritionalPlan::class); }
    public function attendances() { return $this->hasMany(Attendance::class); } 
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Fold extends Model { protected $fillable = ["measurement_id", "body_zone", "value"];
public function measurement() { return $this->belongsTo(Measurement::class); } }
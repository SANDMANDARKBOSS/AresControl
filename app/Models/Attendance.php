<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Attendance extends Model { protected $fillable = ["client_id", "date_time"];
public function client() { return $this->belongsTo(Client::class); } }
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginTrack extends Model
{
  protected $table = 'login_track';

  protected $fillable = [
    'user_id',
    'ip'
  ];

  public function user(){
    return $this->belongsTo(User::class);
  }
}

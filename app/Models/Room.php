<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    // TODO : Add RoomController + function index()
	protected $fillable = [
		'name','asso_id',
	];

	protected $table = 'rooms';

    public function asso() {
        return $this->belongsTo('App\Models\Asso');
    }
}

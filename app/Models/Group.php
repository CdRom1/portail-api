<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMembers;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasMembers, SoftDeletes;

    public static function boot() {
        static::created(function ($model) {
            $model->assignRoles('group admin', [
				'user_id' => $model->user_id,
				'validated_by' => $model->user_id,
				'semester_id' => 0,
			], true);
        });
    }

	protected $memberRelationTable = 'groups_roles';

    protected $fillable = [
        'name', 'user_id', 'icon_id', 'visibility_id', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $dates = [
        'deleted_at'
    ];

    // On les caches car on récupère directement le user et la vibility dans le controller
    protected $hidden = [
        'user_id', 'visibility_id'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function visibility() {
    	return $this->belongsTo(Visibility::class, 'visibility_id');
    }
}

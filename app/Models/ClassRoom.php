<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'kode',
        'class_leader_id',
        'class_advisor_id',
        'class_advisor_phone',
        'is_pkl',
        'status',
    ];

    protected $casts = [
        'is_pkl' => 'boolean',
        'status' => 'integer',
    ];

    public function classLeader()
    {
        return $this->belongsTo(User::class, 'class_leader_id');
    }

    public function classAdvisor()
    {
        return $this->belongsTo(User::class, 'class_advisor_id');
    }
}

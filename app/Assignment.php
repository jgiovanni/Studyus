<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use UuidForKey, Filterable;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];

    public function homework()
    {
        return $this->belongsTo(Task::class, 'homework_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function responses()
    {
        return $this->hasMany(AssignmentResponse::class);
    }
}

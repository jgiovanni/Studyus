<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use UuidForKey, Filterable;

    protected $table = 'classrooms';

    protected $guarded = ['id', 'url_id'];

    protected $dates = ['created_at', 'updated_at'];

    protected $with = ['instructors'];

    protected $withCount = ['students'];

    protected $casts = [
        'grades' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'classrooms_users')->withPivot('permissions');
    }
    public function students()
    {
        return $this->users()->where('permissions', null);
    }
    public function instructors()
    {
        return $this->users()->where('permissions', 'manage');
    }

    public function homework_assignments()
    {
        return $this->morphToMany(Task::class, 'assignable');
    }
}

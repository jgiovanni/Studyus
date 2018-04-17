<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Task extends Model
{
    use UuidForKey, Filterable, HasTags;

    protected $guarded = ['id', 'url_id'];

    public $dates = ['created_at', 'updated_at', 'opened_at', 'closed_at'];

    protected $withCount = ['questions'];

    protected $with = ['tags'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function students()
    {
        return $this->morphedByMany(User::class, 'assignable');
    }

    public function classrooms()
    {
        return $this->morphedByMany(ClassRoom::class, 'assignable');
    }

}

<?php

namespace App;
use \Spatie\Tags\HasTags;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use UuidForKey, Filterable, HasTags;
    protected $guarded = [];

    public $dates = ['created_at', 'updated_at'];

    protected $withCount = ['answers'];

    public function homework()
    {
        return $this->belongsTo(Task::class, 'homework_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function responses()
    {
        return $this->hasMany(AssignmentResponse::class);
    }
}

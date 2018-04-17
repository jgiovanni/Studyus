<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use UuidForKey;

    protected $guarded = [];

    public $dates = ['created_at', 'updated_at'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}

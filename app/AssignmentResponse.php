<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class AssignmentResponse extends Model
{
    use UuidForKey, Filterable;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];

    public function assignment()
    {
        $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function answer()
    {
        $this->belongsTo(Answer::class, 'answer_id');
    }

    public function question()
    {
        $this->belongsTo(Question::class, 'question_id');
    }

}

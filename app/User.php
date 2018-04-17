<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use UuidForKey, Notifiable, Filterable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function classrooms()
    {
        return $this->belongsToMany(ClassRoom::class,'classrooms_users')->withPivot('permissions');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function task_assignments()
    {
        return $this->morphToMany(Task::class, 'assignable');
    }

    public function homework_assignments()
    {
        return $this->morphToMany(Task::class, 'assignable');
    }

    /*public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }*/

}

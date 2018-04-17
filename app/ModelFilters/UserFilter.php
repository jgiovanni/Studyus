<?php namespace App\ModelFilters;

class UserFilter extends Filter
{
    use Sortable;
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = ['classrooms', 'homeworks', 'assignments'];

    public function notUsers($users)
    {
        return $this->whereNotIn('id', $users);
    }

    public function users($users)
    {
        return $this->whereIn('id', $users);
    }

    public function instructors($instructors)
    {
        if (is_array($instructors))
            return $this->whereIn('users.id', $instructors);
        else
            return $this->where('users.id', $instructors);
    }

    public function students($students)
    {
        return $this->whereIn('id', $students);
    }

    public function role($role)
    {
        return $this->whereHas('roles', function ($q) use ($role) {
            return $q->where('name', $role);
        });
    }


}

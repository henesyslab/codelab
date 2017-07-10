<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gitlab_id',
        'gitlab_api',
        'name',
        'path',
        'description',
        'notes',
    ];

    /**
     * Define o relacionamento com a classe Project.
     *
     * @return \App\Models\Project
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}

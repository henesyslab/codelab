<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gitlab_id',
        'client_id',
        'name',
        'path',
        'description',
        'tag_list',
        'notes',
    ];

    /**
     * Define o relacionamento com a classe Client.
     *
     * @return \App\Models\Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

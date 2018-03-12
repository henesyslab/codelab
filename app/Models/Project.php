<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'sqlite';

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
     * Converte o array de tags para uma string.
     *
     * @param  array  $value
     * @return string
     */
    public function setTagListAttribute($value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        return $this->attributes['tag_list'] = $value;
    }

    /**
     * Converte a lista de tags para um array.
     *
     * @param  string $value
     * @return array
     */
    public function getTagListAttribute($value)
    {
        $value = explode(',', $value);
        $value = array_filter(array_map(function ($item) {
            return trim($item);
        }, $value));

        return $value;
    }

    /**
     * Define o relacionamento com a classe Client.
     *
     * @return \App\Models\Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Define o relacionamento com a classe Client.
     *
     * @return \App\Models\Client
     */
    public static function fetchAll()
    {
        $projectTable = with(new self())->getTable();
        $clientTable = with(new Client())->getTable();

        return self::with('client')
            ->select($projectTable.'.*')
            ->join($clientTable, $clientTable.'.id', '=', $projectTable.'.client_id')
            ->orderBy($clientTable.'.name')
            ->orderBy($projectTable.'.name')
            ->get();
    }
}

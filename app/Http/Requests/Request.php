<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Vinkla\GitLab\Facades\GitLab;

abstract class Request extends FormRequest
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        Validator::extend('gitlab_unique', function ($attribute, $value, $parameters, $validator) {
            return empty(GitLab::api('users')->lookup($value));
        });
    }

    /**
     * Filtra os dados postados antes de serem validados.
     */
    protected function sanitize()
    {
        $input = $this->all();
        $input = array_filter($this->all());
        $this->replace($input);
    }
}

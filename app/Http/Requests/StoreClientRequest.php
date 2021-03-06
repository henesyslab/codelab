<?php

namespace App\Http\Requests;

class StoreClientRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();

        return [
            'name' => 'required|max:255',
            'path' => 'required|max:12|gitlab_unique',
            'description' => 'max:255',
        ];
    }
}

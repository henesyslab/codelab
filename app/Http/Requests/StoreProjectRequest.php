<?php

namespace App\Http\Requests;

class StoreProjectRequest extends Request
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
            'client_id' => 'required|integer',
            'name' => 'required|max:255',
            'path' => 'required|max:12',
            'description' => 'max:255',
        ];
    }
}

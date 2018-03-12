<?php

namespace App\Http\Requests\FastWay;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class TaskRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
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
            'description' => 'required',
        ];
    }
}

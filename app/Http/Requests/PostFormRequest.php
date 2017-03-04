<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\User;
use Auth;

class PostFormRequest extends Request
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
        return [
            'post_title' => 'required|unique:posts|max:255',
            'post_body' => 'required',
            'post_author_name' => 'required|max:5',
            'post_author_email' => 'required|max:5|email',
        ];
    }
}

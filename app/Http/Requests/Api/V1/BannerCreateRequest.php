<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BannerCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title" => ['required'],
            "description" => ['required'],
            "backgroundImage" => ["required",'image','mimes:png,jpg,jpeg,gif,jfif,webp','max:2048'],
            "number" => ['required','numeric'],
            "whatsapp" => ['required']
        ];
    }
}

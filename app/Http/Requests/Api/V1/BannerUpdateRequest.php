<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
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
        $method = $this->method();
        if($method == 'PUT') {
            return [
                "title" => ['string'],
                "description" => ['string'],
                "backgroundImage" => ['image','mimes:png,jpg,jpeg,gif,jfif,webp','max:2048'],
                "number" => ['numeric'],
                "whatsapp" => ['url']
            ];
        }else {
            return [
                "title" => ['sometimes','required'],
                "description" => ['sometimes','required'],
                "backgroundImage" => ["sometimes","required",'image','mimes:png,jpg,jpeg,gif,jfif,webp','max:2048'],
                "number" => ['sometimes','required','numeric'],
                "whatsapp" => ['sometimes','required','url']
            ];
        };
    }
}

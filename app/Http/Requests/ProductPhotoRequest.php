<?php

namespace CodeShopping\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPhotoRequest extends FormRequest
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
        return !$this->route('photo') ? $this->rulesCreate() : $this->rulesUpdate();
    }

    private function rulesCreate()
    {
        return [
            'photos' => 'required|array',
            'photos.*' => 'required|image'
        ];
    }

    private function rulesUpdate()
    {
        return [
            'photo' => 'required|image'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'price' => 'required|numeric|between:0,99999999.99'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'အမည် ထည့်ရန် လိုအပ်သည်။',
            'name.string' => 'အမည်သည် စာသား ဖြစ်ရမည်။',
            'price.required' => 'ပိုက်ဆံ ထည့်ရန် လိုအပ်သည်။',
            'price.numeric' => 'ပိုက်ဆံသည် ဂဏန်း ဖြစ်ရမည်။',
            'price.between' => 'ပိုက်ဆံသည် သိန်းတစ်ရာထက် မပိုရပါ။'
        ];
    }
}

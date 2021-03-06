<?php

namespace GetCandy\Api\Http\Requests\Products;

use GetCandy\Api\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ruleset = [
            'family_id' => 'hashid_is_valid:product_families',
            'layout_id' => 'hashid_is_valid:layouts',
            'attribute_data' => 'array',
        ];

        $attributes = app('api')->products()->getAttributes($this->product);
        $defaultChannel = app('api')->channels()->getDefaultRecord();
        $defaultLanguage = app('api')->languages()->getDefaultRecord();

        foreach ($attributes as $attribute) {
            if ($attribute->required) {
                $rulestring = 'attribute_data.'.$attribute->handle.'.'.$defaultChannel->handle.'.'.$defaultLanguage->lang;
                // $ruleset[$rulestring] = 'required';
            }
        }

        return $ruleset;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'attribute_data.*.*.*.required' => 'This field is required',
        ];
    }
}

<?php

namespace App\Validations;

/**
 * defines rules for form related to user
 *
 * Class JobValidator
 * @package App\Validations
 */
class JobValidator extends Validator
{
    /**
    * Custom messages for validation
    *
    * @param $type
    * @return array
    */
    public function messages($type)
    {
        $messages = [];

        switch($type) {
            case 'slug':
                $messages = ['Slug is invalid.'];
                break;
            default:
                break;
        }

        return $messages;
    }

    /**
    * validation rules
    *
    * @return array
    */
    protected function rules($type, $data)
    {
        $rules =  [];

        switch($type) {
            case 'create':
                $rules = [
                    'title'       => 'required|min:6|max:50',
                    'description' => 'required|min:25|max:500'
                ];
                break;
            case 'update':
                $rules = [  
                    'title'       => 'min:8|max:200',
                    'description' => 'min:25|max:500'
                ];
                break;
            case 'slug':
                $rules = [  
                    'slug'       => 'required|exists:jobs',
                ];
                break;
        }

        return $rules;
    }
}

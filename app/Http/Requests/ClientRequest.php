<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'client_name' => 'required|string',
            'client_phone' => 'required|string',
            'client_email' => 'required|string',
            'client_card' => 'required|string',
            'client_money' => '',
            'client_data' => '',
        ];
        switch($this->getMethod()){
          case 'POST':
          return $rules;
          case 'PUT':
            return [
              'client_id' => 'required|integer|exists:clients,id',
              'client_name'  => 'required'
            ] + $rules;
            case 'DELETE':
            return [
              'client_id' => 'required|integer|exists:clients,id'
            ];
        }
    }
    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.unique'  => 'This email is already taken',
            'email.format'  => 'This email must contain @ and dot symbols and lowercase letters',
        ];
    }
    public function all($keys = null)
    {
      // return $this->all();
      $data = parent::all($keys);
      switch ($this->getMethod())
      {
        // case 'PUT':
        // case 'PATCH':
        case 'DELETE':
          $data['email'] = $this->route('email');
      }
      return $data;
    }
}

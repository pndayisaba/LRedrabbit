<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class SignupValidate extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
      return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      'email'=> 'required',
      'password'=> 'required',
      'password2'=> 'required|same:password'
    ];
  }

  public function messages(): array
  {
    return [
      'email.required'=> 'Required',
      'password.required'=> 'Required',
      'password2.required'=> 'Required'
    ];
  }

  /**
   * Configure the validator instance.
   *
   * @param  \Illuminate\Validation\Validator  $validator
   * @return void
   */
  public function withValidator($validator): void
  {
    // After the $rules are evaluated, attempt 
    // registering new user and set errors
    // as needed;
    $validator->after(function ($validator) {
    $input = [
      'first_name'=> Input::get('first_name'),
      'last_name'=> Input::get('last_name'),
      'email'=> Input::get('email'),
      'password'=> Input::get('password')
    ];
  
    $newUser = DB::select('CALL add_user_spi(:first_name, :last_name, :email, :password)', $input);
    $resp = !empty($newUser) && is_array($newUser) ? (array) $newUser[0] : [ ];
    $errorMessage = null;
    $fieldName = 'unknown';
    if(!empty($resp) 
      && array_key_exists('message', $resp) 
      && stripos($resp['message'], $input['email'])
    ) 
    {
      $fieldName = 'email';
      $errorMessage = $resp['message'];
    }
    else if($resp['success'] == 0)
      $errorMessage = 'An unknown error occurred!';

      if (!empty($errorMessage)) {
        $validator->errors()->add($fieldName, $errorMessage);
      }
    });
  }
}

<?php
namespace Modules\Home\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessTokenRequest extends FormRequest{
    private $rules = [
    	"auth/token" => [
    		"username" => "required|alpha_dash|between:3,20",
            "password" => "required|alpha_dash|between:6,20",
    	],
        "auth/token/refresh" => [
            "refresh_token" => "required",
        ],
    ];
	/**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        $rules = [];
        if(!empty($this->rules)){
            foreach ($this->rules as $action => $rule) {
                $regex = "*/" . $action;
                if($this->is($regex)){
                    $rules = $rule;
                    break;
                }
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
        	"username.required" => config("exceptions.USER_USERNAME_REQUIRED"),
            "username.alpha_dash" => config("exceptions.USER_USERNAME_ALPHA_DASH"),
            "username.between" => config("exceptions.USER_USERNAME_BETWEEN"),
            "username.unique" => config("exceptions.USER_USERNAME_UNIQUE"),
            "password.required" => config("exceptions.USER_PASSWORD_REQUIRED"),
            "password.alpha_dash" => config("exceptions.USER_PASSWORD_ALPHA_DASH"),
            "password.between" => config("exceptions.USER_PASSWORD_BETWEEN"),
            "password.confirmed" => config("exceptions.USER_PASSWORD_CONFIRMED"),
            "refresh_token.required" => config("exceptions.REFRESH_TOKEN_REQUIRED"),
        ];
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
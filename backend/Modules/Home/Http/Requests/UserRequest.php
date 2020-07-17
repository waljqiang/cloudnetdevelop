<?php
namespace Modules\Home\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest{
    private $rules = [
    	"user/register" => [
    		"username" => "required|alpha_dash|between:3,20|unique:users",
    		"password" => "required|alpha_num|between:6,20|confirmed",
            "phonecode" => "required|exists:country_code,phonecode",
    		"phone" => "required|phone:phonecode",
    		"email" => "required|email"
    	],
        "develop" => [
            "name" => "required|regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]{1,20}$/iuD",
            "idcard" => "required|alpha_num|max:50",
            "enterprise" => "required|regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]{1,100}$/iuD",
            "enterprise_des" => "required|max:1000",
            "enterprise_code" => "required|alpha_num|max:50",
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
                $regex = "*" . $action;
                if($this->is($regex)){
                    $rules = $rule;
                    break;
                }
            }
        }
        return $rules;
    }

    //条件验证
/*    protected function getValidatorInstance(){
        $validator = parent::getValidatorInstance();
        return $validator->sometimes("area", "exists:area,code", function ($input) {
            return is_numeric($input->area);
        });
    }*/

    public function messages()
    {
        return [
        	"username.required" => config("exceptions.USER_USERNAME_REQUIRED"),
        	"username.alpha_dash" => config("exceptions.USER_USERNAME_ALPHA_DASH"),
        	"username.between" => config("exceptions.USER_USERNAME_BETWEEN"),
        	"username.unique" => config("exceptions.USER_EXISTS"),
            "username.exists" => config("exceptions.USER_NO_EXISTS"),
        	"password.required" => config("exceptions.USER_PASSWORD_REQUIRED"),
        	"password.alpha_num" => config("exceptions.USER_PASSWORD_ALPHA_DASH"),
        	"password.between" => config("exceptions.USER_PASSWORD_BETWEEN"),
            "password.confirmed" => config("exceptions.USER_PASSWORD_CONFIRMED"),
            "phonecode.required" => config("exceptions.COUNTRY_PHONECODE_REQUIRED"),
            "phonecode.required_without" => config("exceptioins.PARAMS_INVALID"),
            "phonecode.required_with" => config("exceptions.PHONE_PHONECODE_REQUIRED"),
            "phonecode.exists" => config("exceptions.COUNTRY_PHONECODE_EXISTS"),
            "phone.required" => config("exceptions.PHONE_REQUIRED"),
            "phone.required_without" => config("exceptions.PARAMS_INVALID"),
            "phone.required_with" => config("exceptions.PHONE_PHONECODE_REQUIRED"),
        	"phone.phone" => config("exceptions.PHONE"),
            "email.required" => config("exceptions.EMAIL_REQUIRED"),
            "email.required_without" => config("exceptions.PARAMS_INVALID"),
        	"email.email" => config("exceptions.EMAIL"),
            "name.required" => config("exceptions.NAME_REQUIRED"),
            "name.required" => config("exceptions.NAME_REGX"),
            "idcard.required" => config("exceptions.IDCARD_REQUIRED"),
            "idcard.alpha_num" => config("exceptions.IDCARD_ERROR"),
            "idcard.max" => config("exceptions.IDCARD_ERROR"),
            "enterprise.required" => config("exceptions.COMPANY_REQUIRED"),
            "enterprise.regex" => config("exceptions.COMPANY_REGEX"),
            "enterprise_des.required" => config("exceptions.COMPANY_DES_REQUIRED"),
            "enterprise_des.max" => config("exceptions.COMPANY_DES_REQUIRED"),
            "enterprisecode.required" => config("exceptions.COMPANY_CODE_REQUIRED"),
            "enterprisecode.alpha_num" => config("exceptions.COMPANY_CODE_ERROR"),
            "enterprisecode.max" => config("exceptions.COMPANY_CODE_ERROR"),
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
<?php
namespace Modules\License\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LicenseRequest extends FormRequest{
    private $rules = [
        'generate' => [
            'company' => 'required',
            'domain' => 'required',
            'expire_in' => 'required|numeric',
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
                $regex = '*/' . $action;
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
            'company.required' => config('exceptions.COMPANY_REQUIRED'),
            'domain.required' => config('exceptions.COMPANY_DOMAIN_REQUIRED'),
            'expire_in.required' => config('exceptions.LICENSE_EXPIRE_IN_REQUIRED'),
            'expire_in.numeric' => config('exceptions.LICENSE_EXPIRE_IN_NUMERIC'),
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
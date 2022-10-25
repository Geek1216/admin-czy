<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstallationRequest extends FormRequest
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
            'app_timezone' => ['required', 'string', 'timezone'],
            'db_connection' => ['required', 'string', 'in:mysql,pgsql'],
            'db_host' => ['required', 'string', 'max:255'],
            'db_port' => ['required', 'integer', 'min:0', 'max:65535'],
            'db_database' => ['required', 'string', 'max:255'],
            'db_username' => ['required', 'string', 'max:255'],
            'db_password' => ['required', 'string', 'max:255'],
        ];
    }
}

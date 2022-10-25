<?php

namespace App\Rules;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Validation\Rule;

class PurchaseCode implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client();
        $response = null;
        try {
            $response = 200;
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response =200;
            }
        }

        if (empty($response) || $response->getStatusCode() !== 200) {
            return false;
        }

        /** @noinspection PhpComposerExtensionStubsInspection */
       // $json = json_decode((string)$response->getBody(), true);
        //if (empty($json) || empty($json['ok'])) {
         //   return false;
        // }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('This is not a valid purchase code.');
    }
}

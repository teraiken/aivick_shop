<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use App\Http\Requests\AddressRequest;

class AddressTest extends TestCase
{
    /**
     * @dataProvider validationProvider
     * @return void
     */
    public function testValidation($inData, $outFail, $outMessage)
    {
        $request = new AddressRequest();
        $rules = $request->rules();
        $messages = $request->messages();
        $validator = Validator::make($inData, $rules, $messages);
        $result = $validator->fails();
        $this->assertEquals($outFail, $result);
        $messages = $validator->errors()->getMessages();
        $this->assertEquals($outMessage, $messages);
    }

    public function validationProvider()
    {
        return [
            'success' => [
                [
                    'name' => 'aaa',
                    'postal_code' => '1000001',
                    'pref_id' => 13,
                    'address1' => '千代田区',
                    'address2' => '千代田',
                    'phone_number' => '00000000000',
                ],
                false,
                [],
            ],
            'empty all fields' => [
                [],
                true,
                [
                    'name' => ['名は必ず指定してください。'],
                    'postal_code' => ['郵便番号は必ず指定してください。'],
                    'pref_id' => ['都道府県は必ず指定してください。'],
                    'address1' => ['市区町村は必ず指定してください。'],
                    'address2' => ['住所は必ず指定してください。'],
                    'phone_number' => ['電話番号は必ず指定してください。'],
                ],
            ],
        ];
    }
}
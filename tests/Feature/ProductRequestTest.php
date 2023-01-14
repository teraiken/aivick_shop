<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\UploadedFile;

class ProductRequestTest extends TestCase
{
    /**
     * @dataProvider validationProvider
     * @return void
     */
    public function testValidation($inData, $outFail, $outMessage)
    {
        $request = new ProductRequest();
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
                    'name' => '弁当',
                    'image' => UploadedFile::fake()->image('弁当.jpeg'),
                    'introduction' => '弁当です。弁当です。弁当です。弁当です。弁当です。',
                    'price' => 500,
                    'stock' => 10,
                    'status' => 9,
                ],
                false,
                [],
            ],
            'empty all fields' => [
                [],
                true,
                [
                    'name' => ['名は必ず指定してください。'],
                    'image' => ['画像は必ず指定してください。'],
                    'introduction' => ['説明文は必ず指定してください。'],
                    'price' => ['価格は必ず指定してください。'],
                    'stock' => ['在庫数は必ず指定してください。'],
                    'status' => ['ステータスは必ず指定してください。'],
                ],
            ],
        ];
    }
}
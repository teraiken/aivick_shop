<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use App\Http\Requests\ProductRequestForStore;
use Illuminate\Http\UploadedFile;

class ProductRequestForStoreTest extends TestCase
{
    /**
     * @dataProvider validationProvider
     * @return void
     */
    public function testValidation($inData, $outFail, $outMessage)
    {
        $request = new ProductRequestForStore();
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
                    'start_date' => '2024-01-01',
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
                    'start_date' => ['開始日は必ず指定してください。'],
                ],
            ],
            'period validation' => [
                [
                    'name' => '弁当',
                    'image' => UploadedFile::fake()->image('弁当.jpeg'),
                    'introduction' => '弁当です。弁当です。弁当です。弁当です。弁当です。',
                    'price' => 500,
                    'stock' => 10,
                    'start_date' => '2023-01-01',
                    'end_date' => '2022-01-01',
                ],
                true,
                [
                    'start_date' => ['開始日には、本日以降の日付を指定してください。'],
                    'end_date' => ['終了日には、開始日以降の日付を指定してください。'],
                ],
            ],
        ];
    }
}

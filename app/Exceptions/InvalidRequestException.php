<?php

namespace App\Exceptions;

use App\Http\Requests\Request;
use Exception;
use Throwable;

class InvalidRequestException extends Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            //json()方法第二个参数就是HTTP返回码
            return response()->json(['msg' => $this->message],$this->code);
        }

        return view('pages.error',['msg' => $this->message]);
    }
}

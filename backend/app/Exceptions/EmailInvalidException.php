<?php

namespace App\Exceptions;

use Exception;

class EmailInvalidException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view('oauth.emailInvalid', [], 400);
    }
}

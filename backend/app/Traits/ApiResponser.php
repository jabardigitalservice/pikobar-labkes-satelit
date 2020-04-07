<?php

namespace App\Traits;

// use fractal;
// use Illuminate\Support\Collection;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Support\Facades\Cache;

trait ApiResponser
{

	protected function errorResponse($message, $code)
	{ 
		return response()->json([
			'error' => $message,
			'code' => $code
		], $code);
	}

	private function successResponse($data, $code)
	{
		return response()->json($data, $code);
	}

}
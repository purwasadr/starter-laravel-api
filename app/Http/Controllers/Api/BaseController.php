<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

// agar tidak duplikat menulis respons dicontroller suatu fiture
class BaseController extends Controller
{
    // respone ketia sukses
    public function successResponse($data = [], $msg = 'success', $code = null)
    {
        $code = $code ?? 200;

        return response()->json([
            'success' => true,
            'data' => (object) $data,
            'message' => $msg,
        ], $code);
    }

    // respon ketika gagal
    public function errorResponse($msg = 'failed', $code = 400)
    {
        return response()->json([
            'success' => false,
            'data' => (object) [],
            'message' => $msg,
        ], $code);
    }

    public function generateId()
    {
        $date = date('Ymd');
        $random = rand(100, 999);
        $generateId = $date.$random;

        return $generateId;
    }
}

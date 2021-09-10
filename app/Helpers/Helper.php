<?php

use App\Models\InventoryPackagingItem;
use App\Models\InventoryUnitItem;
use Illuminate\Support\Facades\DB;

    function APIResponse($success, $code, $error, $message, $data = [])
    {
        $responseArr = array();
        $responseArr['success'] = $success;
        $responseArr['code'] = $code;
        $responseArr['error'] = $error;
        $responseArr['message'] = $message;
        if ($data) {
            $responseArr['results'] = $data;
        } else {
            $responseArr['results'] = '';
        }
        return $responseArr;
    }
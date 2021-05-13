<?php

namespace App\Http\Controllers;

use App\Http\Services\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createTransfer(Request $request, TransferService $transferService): Response
    {
        $validate = Validator::make($request->all(),[
           'sender' => 'required|integer',
           'receiver' => 'required|integer',
           'amount' => 'required|string',
           'currency' => 'required|string|min:3'
        ]);

        if ($validate->fails()) {
            return response($validate->errors(), JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $transaction = $transferService->createTransfer($request->all());
        } catch (\RuntimeException $e) {
            Log::error($e->getMessage());
            return response($e->getMessage());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response('Fund transfer failed, please try again later', $e->getCode());
        }

        return response( ['id' => $transaction->id], JsonResponse::HTTP_CREATED);
    }
}

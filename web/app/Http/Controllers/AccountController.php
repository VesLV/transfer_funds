<?php

namespace App\Http\Controllers;

use App\Http\Services\AccountService;
use App\Http\Services\TransferService;
use App\Http\Utils\ResponseBuilder;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * @param Request $request
     * @param AccountService $accountService
     * @return \Illuminate\Http\Response
     */
    public function createAccount(Request $request, AccountService $accountService): Response
    {
        $validated = Validator::make($request->all(), [
            'client' => 'required|integer',
            'currency' => 'required|string|min:3',
            'balance' => 'required|string'
        ]);

        if ($validated->fails()) {
            return response($validated->errors(), JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $accountService->createAccount($request->all());
            return response('', JsonResponse::HTTP_CREATED);
        } catch (\RuntimeException $e) {
            Log::error($e->getMessage());
            return response($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response('Internal server error, please try again', $e->getCode());
        }
    }

    /**
     * @param $accountId
     * @return \Illuminate\Http\Response
     */
    public function getAccount($accountId): Response
    {
        $account = Account::find($accountId);
        if (!$account) {
            return response('Account not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return response(ResponseBuilder::accountResponse($account), JsonResponse::HTTP_OK);
    }

    /**
     * @param $accountId
     * @param Request $request
     * @param TransferService $transferService
     * @return \Illuminate\Http\Response
     */
    public function getAccountHistory($accountId, Request $request, TransferService $transferService): Response
    {
        try {
           $response = $transferService->getTransferHistory($accountId, $request->all());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response('Retreiving transfer history failed, please try again.', $e->getCode());
        }

        return response($response, JsonResponse::HTTP_OK);
    }
}

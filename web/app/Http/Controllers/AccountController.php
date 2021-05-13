<?php

namespace App\Http\Controllers;

use App\Http\Services\AccountService;
use App\Http\Utils\ResponseBuilder;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

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

    public function getAccountHistory($accountId)
    {

    }
}

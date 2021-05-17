<?php

namespace App\Http\Controllers;

use App\Http\Services\ClientService;
use App\Models\Client;
use App\Http\Utils\ResponseBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{

    /**
     * @param Request $request
     * @param ClientService $clientService
     * @return Response
     */
    public function createClient(Request $request, ClientService $clientService): Response
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'surname' => 'required|string|min:3',
            'country' => 'required|string'
        ]);

        if ($validated->fails()) {
            return response($validated->errors(), JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $clientService->createClient($request->all());
            return response('', JsonResponse::HTTP_CREATED);
        } catch (\RuntimeException $e) {
            Log::error($e->getMessage());
            return response('Client already exists', JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response('Internal server error, please try again', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $clientId
     * @return Response
     */
    public function getClient($clientId): Response
    {
        $client = Client::find($clientId);
        if (!$client) {
            return response('Client not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return response(ResponseBuilder::clientResponse($client), JsonResponse::HTTP_OK);
    }
}

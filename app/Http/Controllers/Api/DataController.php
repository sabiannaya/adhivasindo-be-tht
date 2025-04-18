<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DataService;
class DataController extends Controller
{
    protected $dataService;
    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }
    public function search(Request $request)
    {
        $serviceResponse = $this->dataService->search($request);

        return $serviceResponse;
    }
}

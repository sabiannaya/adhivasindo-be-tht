<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DataService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search(Request $request)
    {
        try {
            $response = Http::get('https://ogienurdiana.com/career/ecc694ce4e7f6e45a5a7912cde9fe131');

            // Get raw body as string
            $rawText = $response->json();

            $lines = explode("\n", $rawText['DATA']);

            $parsed = array_map(function ($item) {
                return explode('|', $item);
            }, $lines);

            if ($request->NIM) {
                $result = array_filter($parsed, function ($item) use ($request) {
                    return isset($item[2]) && $item[2] === $request->NIM;
                });

                if (empty($result)) {
                    http_response_code(404); // 404 NOT FOUND
                    return $this->sendError([], __('NIM Not Found'));
                }

                $result = array_values($result);
                $result = array_map(function ($item) {
                    return [
                        'YMD' => $item[0] ?? null,
                        'NAMA' => $item[1] ?? null,
                        'NIM'  => $item[2] ?? null,
                    ];
                }, $result);

                http_response_code(200); // 200 OK

                return $this->sendSuccess([
                    'mahasiswa' => $result,
                ], __('messages.messages.request.success'));
            }
            if ($request->NAMA) {
                $result = array_filter($parsed, function ($item) use ($request) {
                    return isset($item[1]) && $item[1] === $request->NAMA;
                });

                if (empty($result)) {
                    http_response_code(404); // 404 NOT FOUND
                    return $this->sendError([], __('Nama Not Found'));
                }

                $result = array_values($result);
                $result = array_map(function ($item) {
                    return [
                        'YMD' => $item[0] ?? null,
                        'NAMA' => $item[1] ?? null,
                        'NIM'  => $item[2] ?? null,
                    ];
                }, $result);

                http_response_code(200); // 200 OK

                return $this->sendSuccess([
                    'mahasiswa' => $result,
                ], __('messages.messages.request.success'));
            }
            if ($request->YMD) {
                $result = array_filter($parsed, function ($item) use ($request) {
                    return isset($item[0]) && $item[0] === $request->YMD;
                });

                if (empty($result)) {
                    http_response_code(404); // 404 NOT FOUND
                    return $this->sendError([], __('YMD Not Found'));
                }

                $result = array_values($result);
                $result = array_map(function ($item) {
                    return [
                        'YMD' => $item[0] ?? null,
                        'NAMA' => $item[1] ?? null,
                        'NIM'  => $item[2] ?? null,
                    ];
                }, $result);

                http_response_code(200); // 200 OK

                return $this->sendSuccess([
                    'mahasiswa' => $result,
                ], __('messages.messages.request.success'));
            }
        } catch (Exception $e) {
            http_response_code(500); // 500 INTERNAL SERVER ERROR
            $message = $this->onDebug ? $e->getMessage() : __('messages.messages.request.error');
            return $this->SendError([], $message);
        }
    }
}

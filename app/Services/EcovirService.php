<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EcovirService
{
    private const BASE_URL = 'https://ecovir.usfa.edu.bo:49233';
    private const ABANDONOS_URI = '/api/report/listabandon';
    private const ESTUDIANTES_URI = '/api/report/listoldstudents';
    private const DESERCIONES_URI = '/api/report/listdesert';

    public const STEUDENTS_KEY = 'Students';
    public const DROPOUT_KEY = 'Dropouts';
    public const ATTRITION_KEY = 'Attrition';

    private array $response;

    public function sync(string $type)
    {
        if ($type == self::STEUDENTS_KEY) {
            $this->getEstudiantes();
        }

        if ($type == self::DROPOUT_KEY) {
            $this->getAbandonos();
        }

        if ($type == self::ATTRITION_KEY) {
            $this->getDeserciones();
        }

        return $this->getResponse();
    }

    private function setCallConfig() {
        return Http::withBasicAuth(
                config('services.ecovir.user'),
                config('services.ecovir.pass')
            )->withoutVerifying();
    }

    private function setResponse($message, $success = false, $data = null) {
        //publicar en la table logs en error
        $this->response = [
            'success' => $success,
            'data' => $data,
            'message' => ''
        ];
    }

    private function getResponse() {
        return $this->response;
    }

    private function getEstudiantes() {
        try {
            $response = $this->setCallConfig()->get(self::BASE_URL . self::ESTUDIANTES_URI, []);
            $this->setResponse(null, true, $response->json());
        } catch (\Throwable $e) {
            $this->setResponse($e->getMessage());
        }
    }

    private function getAbandonos() {
        try {
            $response = $this->setCallConfig()->get(self::BASE_URL . self::ABANDONOS_URI, []);
            $this->setResponse(null, true, $response->json());
        } catch (\Throwable $e) {
            $this->setResponse($e->getMessage());
        }
    }

    private function getDeserciones() {
        try {
            $response = $this->setCallConfig()->get(self::BASE_URL . self::DESERCIONES_URI, []);
            $this->setResponse(null, true, $response->json());
        } catch (\Throwable $e) {
            $this->setResponse($e->getMessage());
        }
    }
}
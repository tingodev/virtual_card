<?php

namespace Michael\virtualcard;
use GuzzleHttp\Client;

class VirtualCard
{
    public $username;
    public $password;
    public $program_id;

    private $base_url = "https://sandbox.gtpportal.com/rest";
    private $endpoint;
    

    public function __construct(string $username, string $password, int $programId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->program_id = $programId;
    }

    private function __call($payload){
        $client = new \GuzzleHttp\Client();
        $response =  $client->request('POST',$this->base_url.$this->endpoint,$payload);
        return $response;
    }

    public function testPing($requestId, $pingId,)
    {
        $this->endpoint = '/api/v1/ping';
        $payload = ["pingId"=> $pingId];
        $response = $this->__call($payload);

        return $response;
    }
}
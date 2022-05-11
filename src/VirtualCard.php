<?php

namespace TrillzGlobal\VirtualCard;
use \GuzzleHttp\{
    Client,
    Exception\ClientException
};

class VirtualCard
{
    public $username;
    public $password;
    public $program_id;
    public $requestId;
    private $base_url = "https://sandbox.gtpportal.com/rest";
    private $endpoint;
    

    public function __construct(string $username, string $password, int $programId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->program_id = $programId;
    }

    private function call($payload){
        // 'Authorization' => ['Basic'.base64_encode($this->username.':'.$this->password)]

        $client = new Client();
        try{

            $response =  $client->request('POST',$this->base_url.$this->endpoint,[
                'auth'=>[$this->username, $this->password],
                'headers'=>["programId"=>$this->program_id, "requestId"=>$this->requestId, "Content-Type"=>"application/json"],
                'json'=>$payload
            ]);
        }catch(ClientException $e){
            return $e;
        }

        return $response->getBody();
    }

    public function testPing($requestId, $pingId)
    {
        $this->endpoint = '/api/v1/ping';
        $this->requestId = $requestId;
        $payload = ["pingId"=> $pingId];
        $response = $this->call($payload);

        return $response;
    }
}
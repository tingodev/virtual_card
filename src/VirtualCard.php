<?php

namespace TrillzGlobal\VirtualCard;
use \GuzzleHttp\{
    Client,
    Exception\ClientException,
    Exception\RequestException
};

use TrillzGlobal\Validator\Validator as Validator;

class VirtualCard extends Validator
{
    public $username;
    public $password;
    public $program_id;
    public $requestId;
    public $base_url;
    private $endpoint;
    public $payload;
    

    public function __construct(string $base_url, string $username, string $password, int $programId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->program_id = $programId;
        $this->base_url = $base_url;
    }

    private function call(){
        // 'Authorization' => ['Basic'.base64_encode($this->username.':'.$this->password)]
        
        $client = new Client();
        try{

            if(!empty($this->payload)){

                $data =  $client->request('POST',$this->base_url.$this->endpoint,[
                    'auth'=>[$this->username, $this->password],
                    'headers'=>["programId"=>$this->program_id, "requestId"=>$this->requestId, "Content-Type"=>"application/json"],
                    'json'=>$this->payload
                ]);
            }
            else{
                $data =  $client->request('GET',$this->base_url.$this->endpoint,[
                    'auth'=>[$this->username, $this->password],
                    'headers'=>["programId"=>$this->program_id, "requestId"=>$this->requestId, "Content-Type"=>"application/json"]
                ]);
            }
            //Body of response
            $data =(string) $data->getBody(true);
            $response = ["data"=>$data, "status"=>"success"];
        }catch(RequestException $e){
            if($e->hasResponse())
            $data =(string) $e->getResponse()->getBody(true);
            $response = ["data"=>$data, "status"=>"failed"];
        }

        return $response;
    }

    public function testPing($requestId, int $pingId)
    {
        $this->endpoint = '/api/v1/ping';
        $this->requestId = $requestId;
        $payload = ["pingId"=> $pingId];
        $this->payload = $payload;
        $response = $this->call();

        return $response;
    }

    public function generateCard(array $data, $requestId)
    {
        $data = $this->checkPayload($data);
        if($data["status"] == "error"){
            return $data;
        }
        $this->endpoint = '/api/v1/accounts/virtual';
        $this->requestId = $requestId;
        $this->payload = $data["details"];
        $response =  $this->call();

        return $response;
    }

    

   //Transaction List for a given Card
    public function getTransaction(array $data, $requestId){

        $this->requestId = $requestId;
        $accountId = $data['accountId'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $numberOfTrans = $data['numberOfTransaction'];

        $this->endpoint = '/api/v1/accounts/'.$accountId.'/transactions?StartDate='.$startDate.'&EndDate='.$endDate.'&NumberOfTrans='.$numberOfTrans.'&ExtendedData=false';
        $this->payload = '';
        return $this->call();
    }


    //Transfer Fund form one Card to another    
    public function transferBetweenCards(array $data, $requestId){
        $this->requestId = $requestId;
        $data = $this->checkPayloadTransfer($data);
        if($data["status"] == "error"){
            return $data;
        }
        $this->payload = $data["details"];
        $this->requestId = $requestId;
        $this->endpoint = '/api/v1/accounts/fund-transfer';

        return $this->call();
    }

    //Get Balance of a Card

    public function getBalance($accountId, $requestId){
        $this->requestId = $requestId;
        $this->endpoint = '/api/v1/accounts/'.$accountId.'/balance';
        $this->payload = '';
        return $this->call();
    }

    //Transfer to a Card
    public function transferToCard(array $data, $requestId){
        $this->requestId = $requestId;
        $data = $this->checkPayloadTransfer($data);
        if($data["status"] == "error"){
            return $data;
        }
        $this->payload = $data["details"];
        $this->requestId = $requestId;
        $this->endpoint = '/api/v1/accounts/'.$data["accountId"].'/transactions';

    }




}
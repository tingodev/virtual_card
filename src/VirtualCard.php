<?php

namespace TrillzGlobal\VirtualCard;
use \GuzzleHttp\{
    Client,
    Exception\ClientException,
    Exception\RequestException
};

use TrillzGlobal\VirtualCard\Validator as Validator;

class VirtualCard extends Validator
{
    public $username;
    public $password;
    public $program_id;
    public $requestId;
    public $base_url;
    public $endpoint;
    public $payload;
    public $proxy_username;
    public $proxy_password;
    public $proxy;
    public $set_proxy = 0;
    public $proxy_program_id;
    public $proxy_authorization;
    public $method;
    public $debug;
    

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

                
                if($this->set_proxy){
                    $data =  $client->request($this->method,$this->base_url.$this->endpoint,[
                        'headers'=>["programId"=>$this->proxy_program_id,"Authorization"=>$this->proxy_authorization, "requestId"=>$this->requestId, "Content-Type"=>"application/json"],
                        'json'=>$this->payload,
                        'proxy'=>$this->proxy,
                        'verify'=>false,
                        'debug'=>$this->debug
                    ]);

                }else{
                    $data =  $client->request($this->method,$this->base_url.$this->endpoint,[
                        'auth'=>[$this->username, $this->password],
                        'headers'=>["programId"=>$this->program_id, "requestId"=>$this->requestId, "Content-Type"=>"application/json"],
                        'json'=>$this->payload,
                        'debug'=>$this->debug
                    ]);
                }
            }
            else{
                $data =  $client->request($this->method,$this->base_url.$this->endpoint,[
                    'auth'=>[$this->username, $this->password],
                    'headers'=>["programId"=>$this->program_id, "requestId"=>$this->requestId, "Content-Type"=>"application/json"],
                    'debug'=>$this->debug
                ]);
            }
            //Body of response
            $data =(string) $data->getBody(true);
            $response = json_encode(["data"=>json_decode($data, true), "status"=>"success"]);
        }catch(RequestException $e){
            if($e->hasResponse())
            $data =(string) $e->getResponse()->getBody(true);
            if(empty($data)){
                return $e;
            }
            $response =json_encode(["data"=>json_decode($data, true), "status"=>"failed"]);
        }

        return $response;
    }

    public function testPing($requestId, int $pingId)
    {
        $this->endpoint = '/api/v1/ping';
        $this->requestId = $requestId;
        $payload = ["pingId"=> $pingId];
        $this->payload = $payload;
        $this->method ='POST';
        $response = $this->call();

        return $response;
    }

    public function generateCard(array $data, $requestId,string $type ="VIRTUAL")
    {
        $data = $this->checkPayload($data);
        if($data["status"] == "error"){
            return json_encode($data);
        }
        if($type =="INSTANT"){
            $this->endpoint =   "/api/v1/accounts/instant";
        }
        elseif($type=="PERSONAL"){
            $this->endpoint =   "/api/v1/accounts/personalized";
        }
        else{
            $this->endpoint = '/api/v1/accounts/virtual';
        }

        $this->method ='POST';
        
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
        $this->method ='GET';
        $this->endpoint = '/api/v1/accounts/'.$accountId.'/transactions?StartDate='.$startDate.'&EndDate='.$endDate.'&NumberOfTrans='.$numberOfTrans.'&ExtendedData=false';
        $this->payload = '';
        return $this->call();
    }


    //Transfer Fund form one Card to another    
    public function transferBetweenCards(array $data, $requestId){
        $this->requestId = $requestId;
        $data = $this->checkPayloadTransfer($data);
        if($data["status"] == "error"){
            return json_encode($data);
        }
        $this->method ='POST';
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
        $this->method ='GET';
        return $this->call();
    }

    //Transfer to a Card
    public function transferToCard(array $data, $requestId){
        $this->requestId = $requestId;
        $data = $this->checkPayloadToCard($data);
        if($data["status"] == "error"){
            return json_encode($data);
        }
        $this->payload = $data["details"];
        $this->requestId = $requestId;
        $this->endpoint = '/api/v1/accounts/'.$data["details"]["accountId"].'/transactions';
        $this->method ='POST';
        return $this->call();
    }

    public function updateStatus(array $data, $requestId){
        
        $data = $this->checkUpdateStatus($data);
        if($data["status"] == "error"){
            return json_encode($data);
        }
        $this->method ='PATCH';
        $this->endpoint = "/api/v1/accounts/".$data['details']['accountId']."/status";
        $this->payload = $data["details"];
        $this->requestId = $requestId;
        return $this->call();
    }


    public function getCardDetails(array $data, $requestId){
        $this->endpoint = "/api/v1/accounts/".$data['accountId']."/pci-info";
        $this->payload = ["Last4Digits"=>$data['last4Digits']];
        $this->requestId = $requestId;
        $this->method ='POST';
        return $this->call();
    }


}
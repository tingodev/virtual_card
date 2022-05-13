<?php

namespace TrillzGlobal\VirtualCard;
use \GuzzleHttp\{
    Client,
    Exception\ClientException,
    Exception\RequestException
};

class VirtualCard
{
    public $username;
    public $password;
    public $program_id;
    public $requestId;
    private $base_url = "https://sandbox.gtpportal.com/rest";
    private $endpoint;
    public $payload;
    

    public function __construct(string $username, string $password, int $programId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->program_id = $programId;
    }

    private function call($payload){
        // 'Authorization' => ['Basic'.base64_encode($this->username.':'.$this->password)]
        $this->payload = $payload;
        $client = new Client();
        try{

            $response =  $client->request('POST',$this->base_url.$this->endpoint,[
                'auth'=>[$this->username, $this->password],
                'headers'=>["programId"=>$this->program_id, "requestId"=>$this->requestId, "Content-Type"=>"application/json"],
                'json'=>$payload
            ]);
            $response = $response->getBody();
        }catch(RequestException $e){
            if($e->hasResponse())
            $response =(string) $e->getResponse()->getBody(true);
        }

        return $response;
    }

    public function testPing($requestId, int $pingId)
    {
        $this->endpoint = '/api/v1/ping';
        $this->requestId = $requestId;
        $payload = ["pingId"=> $pingId];
        $response = $this->call($payload);

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
        $response =  $this->call($data["details"]);

        return $response;
    }

    private function checkPayload(array $data){
        
        //Compulsory Data
        $compulsory = [
            "accountSource"=>"Account Source must be provided",
            "address1"=> "First Address must be provided",
            "birthDate"=> "Date of Birth to be provided",
            "city"=> "Customer City is needed",
            "country"=> "Country Name needed",
            "emailAddress"=> "Valid Email Should be provided",
            "firstName"=> "Firstname of Customer must be provided",
            "idType"=> "Valid ID Type needed",
            "idValue"=> "Provide valid ID type",
            "lastName"=> "Customer Lastname Needed",
            "countryCode"=> "Country code needed for mobile Number",
            "number"=> "MObile Number needed",
            "preferredName"=> "Prefered Name cannot be empty",
            "referredBy"=> "ReferredBy must be provided",
            "stateRegion"=> "State Region must be provided",
            "subCompany"=> "Sub Company must be provided",
        ];

        $diff = array_diff_key($compulsory, $data);
        if(!empty($diff))
        {
            return ["status"=>"error", "details"=>$diff];;
        }
       $payload =  [
            "accountSource"=> $data["accountSource"],
            "address1"=> $data["address1"],
            "birthDate"=> $data["birthDate"],
            "city"=> $data["city"],
            "country"=> $data["country"],
            "emailAddress"=> $data["emailAddress"],
            "firstName"=> $data["firstName"],
            "idType"=> $data["idType"],
            "idValue"=> $data["idValue"],
            "lastName"=> $data["lastName"],
            "mobilePhoneNumber"=> [
              "countryCode"=> $data["countryCode"],
              "number"=> $data["number"]
            ],
            "preferredName"=> $data["preferredName"],
            "referredBy"=> $data["referredBy"],
            "stateRegion"=> $data["stateRegion"],
            "subCompany"=> $data["subCompany"],
            "expirationDate"=> $data["expirationDate"],
            "middleName"=> $data["middleName"],
            "otherAccountId"=> $data["otherAccountId"],
            "otherCompanyName"=> $data["otherCompanyName"],
            "address2"=> $data["address2"],
            "address3"=> $data["address3"],
            "postalCode"=> $data["postalCode"],
            "alternatePhoneNumber"=> [
              "countryCode"=> $data["countryCode"],
              "number"=> $data["number"]
            ],
            "solId"=> $data["solId"],
            "bvn"=> $data["bvn"]
        ];
        $response = ["status"=>"success", "details"=>$payload];
        return $response;
    }

}
<?php

namespace TrillzGlobal\VirtualCard;

class Validator 
{
    public function checkPayload(array $data){
        
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

    public function checkPayloadTransfer($data){
        $compulsory = [
            "currencyCode"=>"Use a valid country code",
            "fromAccountId"=> "From account cannot be empty",
            "last4Digit"=> "Last 4 digit of card to be provided",
            "paymentType"=> "Payment Type is needed",
            "toAccountId"=> "To Account needed",
            "transferAmount"=> "Transfer Amount Should be provided",
            "fromCardReferenceMemo"=> "To Card Reference Account needed",
            "toCardReferenceMemo"=> "From Card Reference Should be provided"
        ];

        $diff = array_diff_key($compulsory, $data);
        if(!empty($diff))
        {
            return ["status"=>"error", "details"=>$diff];;
        }

        $payload =  [
            "currencyCode"=> $data["currencyCode"],
            "fromAccountId"=> $data["fromAccountId"],
            "last4Digit"=> $data["last4Digit"],
            "paymentType"=> $data["paymentType"],
            "toAccountId"=> $data["toAccountId"],
            "transferAmount"=> $data["transferAmount"],
            "fromCardReferenceMemo"=> $data["fromCardReferenceMemo"],
            "toCardReferenceMemo"=> $data["toCardReferenceMemo"]
        ];

        $response = ["status"=>"success", "details"=>$payload];
        return $response;
    }

    public function checkPayloadToCard($data){
        $compulsory = [
            "currencyCode"=>"Use a valid country code",
            "last4Digits"=> "Last 4 digit of card to be provided",
            "transferType"=> "Payment Type is needed",
            "transferAmount"=> "Transfer Amount Should be provided",
            "referenceMemo"=> "To Card Reference Account needed",
            "accountId" => "accountId must be provided"
        ];

        $diff = array_diff_key($compulsory, $data);
        if(!empty($diff))
        {
            return ["status"=>"error", "details"=>$diff];;
        }

        $payload =  [
            "currencyCode"=> $data["currencyCode"],
            "last4Digits"=> $data["last4Digits"],
            "transferType"=> $data["transferType"],
            "transferAmount"=> $data["transferAmount"],
            "referenceMemo"=> $data["referenceMemo"],
            "accountId"=>$data["accountId"]
        ];

        $response = ["status"=>"success", "details"=>$payload];
        return $response;
    }



}
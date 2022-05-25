## Virtual Card Creation

_Language: PHP_

Package is used to communicate with VISA for generating Cards. It includes 
-Virtual Card
-Instant Card
-Check Balance

`composer require trillzglobal/virtual_card`


# Usage

`use TrillzGlobal\VirtualCard\VirtualCard;`


`$create = new VirtualCard($base_url, $username,$password, $programId);`

$base_url = https://sandbox.gtpportal.com/rest

*Confirm if You can reach host with a ping*

_$requestId -> This is a unique identifier of each request_

`$create->testPing($requestId, $pingId)`


# *To generate a new Card*

_$data -> Contains array of data_

_"accountSource"=>"Account Source must be provided",_
_"address1"=> "First Address must be provided",_
_"city"=> "Customer City is needed",_
_"birthDate"=> "Date of Birth to be provided",_
_"country"=> "Country Name needed",_
_"emailAddress"=> "Valid Email Should be provided",_
_"firstName"=> "Firstname of Customer must be _provided",
_"idType"=> "Valid ID Type needed",_
_"idValue"=> "Provide valid ID type",_
_"lastName"=> "Customer Lastname Needed",_
_"countryCode"=> "Country code needed for mobile _Number",
_"number"=> "MObile Number needed",_
_"preferredName"=> "Prefered Name cannot be empty",_
_"referredBy"=> "ReferredBy must be provided",_
_"stateRegion"=> "State Region must be provided",_
_"subCompany"=> "Sub Company must be provided",_

`$create->generateCard($data, $requestId)`



# *Get transaction for Duration*

_$accountId => Id recieved when card was created_
_$startDate => Date to start reporting 03-FEB_
_$endDate => Date to stop reporting 03-APR_
_$numberOfTrans => Number of record to pull_

`$create->getTransaction($data, $requestId)`


# *Get Card Balance*

_$accountId => Id recieved when card was created_

`$create->getBalance($accountId, $requestId)`


# *Transfer Between Wallets*

_"currencyCode"=>"Use a valid country code",_
_"fromAccountId"=> "From account cannot be empty",_
_"last4Digit"=> "Last 4 digit of card to be provided",_
_"paymentType"=> "Payment Type is needed",_
_"toAccountId"=> "To Account needed",_
_"transferAmount"=> "Transfer Amount Should be provided",_
_"fromCardReferenceMemo"=> "To Card Reference Account needed",_
_"toCardReferenceMemo"=> "From Card Reference Should be provided"_

`$create->transferBetweenCards($data, $requestId)`


# *Transfer to Card*

_"currencyCode"=>"Use a valid country code",_
_"last4Digit"=> "Last 4 digit of card to be provided",_
_"paymentType"=> "Payment Type is needed",_
_"transferAmount"=> "Transfer Amount Should be provided",_
_"referenceMemo"=> "To Card Reference Account needed"_

`$create->transferToCard($data, $requestId)`






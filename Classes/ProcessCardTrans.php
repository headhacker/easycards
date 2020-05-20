<?php

namespace Application\Classes;

class ProcessCardTrans {
	require_once("mailto.php");
	$usertest = "mytestacct";
	$user = "connectcard2020";
	// Password
	$passwordtest = "123testpass";
	$password = "94XD0@0kCD@@31xL1";
	// Site's REST URL
	$urltest = 'https://fts.cardconnect.com:6443/cardconnect/rest';
	$url= 'https://fts.cardconnect.com:8443/cardconnect/rest';
	$merchant_id = "517109121112812";

	function authTransaction($cust_data,$capture="N",$prod = "N",$user_fields="") {
		//echo "\nAuthorization With User Fields Request\n";
		global $url, $user, $password, $urlprod, $userprod, $passwordprod, $merchant_id_prod, $merchant_id;
		if($prod == "Y")
			{
				$url = $urlprod;
				$user = $userprod;
				$password = $passwordprod;
				$merchant_id = $merchant_id_prod;
			}
		//echo $url." ".$user." ".$password."<br>";
		$client = new CardConnectRestClient($url, $user, $password);
	//print_r($ccdata);
		$request = array(
			'merchid'   => $merchant_id,
			'account' 	=> $cust_data['account'],
			'expiry'    => $cust_data['expiry'],
			'cvv2'      => $cust_data['cvv2'],
			'amount'    => $cust_data['amount'],
			'currency'  => "USD",
			'orderid'   => $cust_data['orderid'],
			'name'		=> $cust_data['name'],
			'address' 	=> $cust_data['address'],
			'email' 	=> $cust_data['email'],
			'city' 		=> $cust_data['city'],
			'region' 	=> $cust_data['state'],
			'country' 	=> $cust_data['country'],
			'postal' 	=> $cust_data['postal'],
			'phone' 	=> $cust_data['phone'],
			'profile'	=> $cust_data['profile'],
			'ecomind'	=> "E",
			'tokenize'  => "N",
		);
		//print_r($request);
		// Create user fields
		//$userfields = array("Field1" => "Value1");
		$fields = array($user_fields);
		$authdata["userfields"] = $fields;

		$response = $client->authorizeTransaction($request);
		return $response;
		//print var_dump($response);
	}

	// Authorize Transaction example with profile id
	function authTransactionProfile($cust_data,$capture="N",$prod = "N") {
		global $url, $user, $password, $urlprod, $userprod, $passwordprod, $merchant_id_prod, $merchant_id;
		if($prod == "Y")
			{
				$url = $urlprod;
				$user = $userprod;
				$password = $passwordprod;
				$merchant_id = $merchant_id_prod;
			}
		$client = new CardConnectRestClient($url, $user, $password);

		$request = array(
			'merchid'   => $merchant_id,
			'profile'	=> $cust_data['profile'], //"18837896846173254968"
			'amount'    => $cust_data['amount'],
			'currency'  => "USD",
			'orderid'   => $cust_data['orderid'],
			'ecomind'	=> "E",
			'capture'	=> "Y"
		);

		$response = $client->authorizeTransaction($request);
		//print var_dump($response);
		if(isset($response))
			{
				return $response;
			}else
			{
				$response[]['error'] = "unknown error";
				return $response;
			}
	}

	//--------------------------------------------------------------------------------------------------------------------------------------------------------
	// Capture transaction example
	function captureTransaction($retref,$amount) {
		global $url, $user, $password, $merchant_id;
		echo "\nCapture Transaction Request\n";
		$client = new CardConnectRestClient($url, $user, $password);

		$request = array(
			'merchid' => $merchant_id,
			'amount' => $amount,
			'currency' => "USD",
			'retref' => $retref,
			'ponumber' => "12345",
			'taxamnt' => "007",
			'shipfromzip' => "11111",
			'shiptozip' => "11111",
			'shiptocountry' => "US",
			'postal' => "11111",
			'authcode' => "0001234",
			'invoiceid' => "0123456789",
			'orderdate' => "20140131",
			'frtamnt' => "1",
			'dutyamnt' => "1",
		);

		// Line item details
		// Singe line item
		$item = array (
			'lineno' => "1",
			'material' => "12345",
			'description' => "Item Description",
			'upc' => "0001122334455",
			'quantity' => "5",
			'uom' => "each",
			'unitcost' => "020"
		);
		$items = array($item);

		$request["items"] = $items;

		$response = $client->captureTransaction($request);
		print var_dump($response);
	}

	function createProfile($cust_data,$prod = "Y") {
		global $url, $user, $password, $urlprod, $userprod, $passwordprod, $merchant_id_prod, $merchant_id;

		if($prod == "Y")
			{
				$url = $urlprod;
				$user = $userprod;
				$password = $passwordprod;
				$merchant_id = $merchant_id_prod;
			}
			//echo $url." ".$password." ".$user;
		$client = new CardConnectRestClient($url, $user, $password);

		// Merchant ID
		$request = array(
			'merchid' => $merchant_id,
			'defaultacct' => "Y",
			'account' => $cust_data['account'],
			'expiry'    => $cust_data['expiry'],
			'name' => $cust_data['name'],
			'address' => $cust_data['address'],
			'email' => $cust_data['email'],
			'city' => $cust_data['city'],
			'region' => $cust_data['state'],
			'country' => $cust_data['country'],
			'postal' => $cust_data['postal'],
			'phone' => $cust_data['phone']
		);

			$response = $client->profileCreate($request);

		//print var_dump($response);
		return $response;
}
?>
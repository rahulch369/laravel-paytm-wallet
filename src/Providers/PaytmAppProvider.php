<?php

namespace Anand\LaravelPaytmWallet\Providers;
use Illuminate\Http\Request;

class PaytmAppProvider extends PaytmWalletProvider{

	public function generate(Request $request){
		$checksum = getChecksumFromArray($request->all(), $this->merchant_key);
		return response()->json([ 'CHECKSUMHASH' => $checksum, 'ORDER_ID' => $request->get('ORDER_ID'), 'payt_STATUS'  => '1' ]);
	}

	public function verify(Request $request){
		$paramList = $request->all();
		$return_array = $request->all();
		$paytmChecksum = $request->get('CHECKSUMHASH');

		$isValidChecksum = verifychecksum_e($paramList, $this->merchant_key, $paytmChecksum);
		$return_array["IS_CHECKSUM_VALID"] = $isValidChecksum ? "Y" : "N";
		unset($return_array["CHECKSUMHASH"]);
		$encoded_json = htmlentities(json_encode($return_array));

		return view('paytmwallet::app_redirect.blade.php')->with('json', $encoded_json);
	}
}
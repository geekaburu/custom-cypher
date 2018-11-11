<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CypherController extends Controller
{
    public function getAsciiAdditionValue($key){
		$keySum = 0;
	    $largeAscii = 0;
		$tokens = [
	    	'AhQz3gLrlJksBPbaPnR7' => 'VT7CPKF4o2pHAKhvZg9W',
	    ];

	    $uniqueKey = str_split($tokens[$key]);
	    foreach ($uniqueKey as $char) {
	    	$ascii = ord($char);
	    	$largeAscii < $ascii ? $largeAscii = $ascii : $largeAscii = $largeAscii;
	    	$keySum += $ascii;
	    }

	    $keySum += count($uniqueKey);
	    return $keySum % (255 - $largeAscii);
	}

	public function encrypt(Request $request){
		$string = $request->text;
	    $encrypted = '';
	    $mod = $this->getAsciiAdditionValue($request->key);
	    foreach (str_split($string) as $char) {
	    	 $encrypted.=utf8_encode(chr(ord($char) + $mod));

	    }
	    Log::notice(['IP'=> $request->ip()]);
	    return response()->json([
	    	'original' => $string,
	    	'encrypted' => $encrypted,
	    ]);
	}
	
	public function dencrypt(Request $request){
		$string = utf8_decode($request->text);
		$mod = $this->getAsciiAdditionValue($request->key);
		$dencrypted = '';

		foreach (str_split($string) as $char) {
	    	 $dencrypted.=chr(ord($char) - $mod);
	    }

	    Log::notice(['IP'=> $request->ip()]);
		return response()->json([
	    	'original' => utf8_encode($string),
	    	'dencrypted' => $dencrypted,
	    ]);
	}
}
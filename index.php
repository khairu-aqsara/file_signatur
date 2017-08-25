<?php 

require_once ("vendor/autoload.php");

use Zend\Crypt\PublicKey\RsaOptions;
use Zend\Crypt\PublicKey\Rsa;


$passphrase = md5("khairu.aqsara@hotmail.com");

$rsaOptions = new RsaOptions([
    'pass_phrase' => $passphrase
]);

$rsaOptions->generateKeys([
    'private_key_bits' => 2048,
]);


/**
 * Generating RSA Key
 */
function GenerateKey($rsaOptions)
{

	file_put_contents('private_key.pem', $rsaOptions->getPrivateKey());
	file_put_contents('public_key.pub', $rsaOptions->getPublicKey());
}

/**
 * Sign File
 */

function SignFile($passphrase, $file, $rsaOptions)
{
	GenerateKey($rsaOptions);
	$rsa = Rsa::factory([
	    'private_key'   => 'private_key.pem',
	    'pass_phrase'   => $passphrase,
	    'binary_output' => true,
	]);

	$file = file_get_contents($file);

	$signature = $rsa->sign($file, $rsa->getOptions()->getPrivateKey());
	file_put_contents('halo.sig', $signature);
	return $signature;
}

/**
 * Verify Sign File
 */

function VerifySignFile($file, $signature, $passphrase)
{
	$rsa = Rsa::factory([
	    'private_key'   => 'private_key.pem',
	    'pass_phrase'   => $passphrase,
	    'binary_output' => true,
	]);

	$signature = file_get_contents($signature);
	$file = file_get_contents($file);
	
	$verify    = $rsa->verify($file, $signature, $rsa->getOptions()->getPublicKey());
	return $verify;
}



var_dump(VerifySignFile("halo.txt", "halo.sig", $passphrase));
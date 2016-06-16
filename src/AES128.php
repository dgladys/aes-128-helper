<?php

namespace dgladys\aes128helper;

/**
 * Class AES128
 * Encrypt and decrypt AES-128
 */
class AES128
{
    /**
     * Encrypt data using key
     * @param $data Data to encrypt
     * @param string $key Key necessary to encrypt data
     * @return string
     */
    public static function encrypt($data, $key = "bcb04b7e103a0cd", $mode = MCRYPT_MODE_CBC)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, $mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        $key = $key."\0";
        return base64_encode($iv.mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, $mode, $iv));
    }

    /**
     * Decrypt data using key
     * @param string $encryptedData
     * @param string $key
     * @return string
     */
    public static function decrypt($encryptedData, $key = "bcb04b7e103a0cd", $mode = MCRYPT_MODE_CBC)
    {
        $iv_size =  mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, $mode);
        $ciphertext_dec = base64_decode($encryptedData);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);


        $key = $key."\0";
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, $mode, $iv_dec);
    }
}
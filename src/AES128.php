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
    public static function encrypt($data, $key = "testkey", $mode = MCRYPT_MODE_CBC)
    {
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, $mode);
    }

    /**
     * Decrypt data using key
     * @param string $encryptedData
     * @param string $key
     * @return string
     */
    public static function decrypt($encryptedData, $key = "testkey", $mode = MCRYPT_MODE_CBC)
    {
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryptedData, $mode);
    }
}
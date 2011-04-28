<?php
namespace nano\core\crypt;

/**
 * A simple interface to the mcrypt library.
 * 
 * Requires the Mcrypt PHP extension.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.crypt
 */
class MCrypt {

	/**
	 * Encrypt a string with the given key.
	 * @param mixed $str String to be encrypted.
	 * @param string $key Key to use for encryption.
	 * @return string Base64-encoded ciphertext of the input string.
	 */
	public static function encrypt($str, $key) {
		return base64_encode(trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, trim($str), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}

	/**
	 * Decrypt a string using the given key.
	 * @param mixed $str Base64-encoded ciphertext to be decrypted.
	 * @param string $key Key to use for decryption.
	 * @return string Original (if correct key is supplied!) plaintext string.
	 */
	public static function decrypt($str, $key) {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, trim(base64_decode($str)), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}
}
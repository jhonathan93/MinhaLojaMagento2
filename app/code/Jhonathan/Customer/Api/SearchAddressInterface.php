<?php
/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 */

namespace Jhonathan\Customer\Api;

/**
 * Interface SearchAddressInterface
 * @package Jhonathan\Customer\Api
 */
interface SearchAddressInterface {

    /**
     * @param string $zipcode
     * @return false|mixed|string|string[]
     */
    public function searchAddress(string $zipcode);
}

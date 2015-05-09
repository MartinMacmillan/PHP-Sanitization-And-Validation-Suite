<?php

/**
 * Class InputValidator
 *
 * A suite of static form input validation functions.
 * Every method returns true (valid) or false (not valid).
 */

class InputValidator
{
    /**
     * Validates an e-mail address to PHP's standard.
     * (This should be future-proof provided the host server is kept up-to-date.)
     *
     * @param $email
     * @return bool
     */
    public static function validateEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($email) > 320) {
            return false;
        }

        return true;
    }

    /**
     * Validates text by seeing if the string as at least 1 character; i.e. has the field been filled in?
     *
     * @param $string
     * @return bool
     */
    public static function validateText($string)
    {
        if (is_string($string) && strlen($string) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Checks to see if input is a number.
     *
     * @param $number
     * @return bool
     */
    public static function validateNumber($number)
    {
        return is_numeric($number);
    }

    /**
     * Matches a string against the format of a full UK postcode.
     * This does NOT check for the existence of said postcode; only that the format is correct.
     * Accepts a space and lowercase letters.
     *
     * @param $postcode
     * @return bool
     */
    public static function validateFullUKPostcode($postcode)
    {
        if (preg_match('/^[A-Z]{1,2}[0-9][0-9A-Z]?\s?[0-9][A-Z]{2}$/i', $postcode) === 1) {
            return true;
        }
        return false;
    }

    /**
     * Matches a string against the format of an area code of a UK postcode (i.e. the first 3-4 characters).
     * This does NOT check for the existence of said postcode area; only that the format is correct.
     * Accepts lowercase letters.
     *
     * @param $postcode
     * @return bool
     */
    public static function validatePartialUKPostcode($postcode)
    {
        if (inputValidator::validateFullUKPostcode($postcode)) {
            return true;
        }

        if (preg_match('/^[A-Z]{1,2}[0-9][0-9A-Z]?$/i', $postcode) === 1) {
            return true;
        }

        return false;
    }

    /**
     * Validates against the format of a valid UK landline or mobile number.
     * Obviously, does not test that the number is a genuine working line,
     * only the validity of the supplied format.
     *
     * @param $phoneNumber
     * @return bool
     */
    public static function validateUKTel($phoneNumber)
    {
        $phoneNumber = trim(str_replace(' ', '', $phoneNumber));

        if (strpos($phoneNumber, '+') !== false) {
            $prefix = substr($phoneNumber, 0, 3);
            $suffix = substr($phoneNumber, 3);

            if ($prefix === '+44') {
                $prefix = str_replace($prefix, '+44', '0');
            }
        }

        if (strpos($phoneNumber, '044') !== false) {
            $prefix = substr($phoneNumber, 0, 3);
            $suffix = substr($phoneNumber, 3);

            if ($prefix === '044') {
                $prefix = str_replace($prefix, '+044', '0');
            }
        }

        if (strpos($phoneNumber, '0044') !== false) {
            $prefix = substr($phoneNumber, 0, 4);
            $suffix = substr($phoneNumber, 4);

            if ($prefix === '0044') {
                $prefix = str_replace($prefix, '044', '0');
            }
        }

        $phoneNumber = isset($prefix) && isset($suffix) ? $prefix . $suffix : $phoneNumber;

        if (!is_numeric($phoneNumber)) {
            return false;
        }

        $prefix = substr($phoneNumber, 0, 2);

        if ($prefix === '07' && strlen($phoneNumber) === 11) {
            return true;
        }

        if (($prefix === '01' || $prefix === '02' || $prefix === '03' || $prefix === '08') && strlen($phoneNumber) === 10 || strlen($phoneNumber) === 11) {
            return true;
        }

        return false;
    }
}


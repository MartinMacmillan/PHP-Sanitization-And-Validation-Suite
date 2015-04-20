<?php

/**
 * Class InputSanitizer
 *
 * A suite of static sanitization functions to cleanse data.
 * Every method returns the cleansed input.
 */

class InputSanitizer
{
    public static $noiseWords = array('about','after','all','also','an','and','another','any','are','as','at','be','because','been','before','being','between','both','but','by','came','can','come','could','did','do','each','for','from','get','got','has','had','he','have','her','here','him','himself','his','how','if','in','into','is','it','its','it\'s','like','make','many','me','might','more','most','much','must','my','never','now','of','on','only','or','other','our','out','over','said','same','see','should','since','some','still','such','take','than','that','the','their','them','then','there','these','they','this','those','through','to','too','under','up','very','was','way','we','well','were','what','where','which','while','who','with','would','you','your','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','$','1','2','3','4','5','6','7','8','9','0','"');

    /**
     * @param $string
     * @return string
     *
     * Protects against XSS by sanitizing input through the conversion of HTML characters to entities.
     */
    public static function cleanXss($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @param $string
     * @return string
     *
     * Protects against XSS by sanitizing input through the complete removal of HTML characters.
     */
    public static function sanitizeString($string)
    {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

    /**
     * @param string $email
     * @return string
     *
     * Removes illegal characters from e-mail addresses.
     * This should be compared to the user's original input, and only check validation upon a match.
     * (Validate with the inputValidator class.)
     */
    public static function sanitizeEmail($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * @param $integer
     * @return int
     *
     * Protects against XSS by sanitizing input through casting input into an integer.
     */
    public static function sanitizeInteger($integer)
    {
        return intval($integer);
    }

    /**
     * @param $float
     * @return float
     *
     * Protects against XSS by sanitizing input through casting input into a float.
     */
    public static function sanitizeNumber($float)
    {
        return floatval($float);
    }

    /**
     * @param string $url
     * @return string
     *
     * Protects against XSS by URL encoding input.
     */
    public static function urlEncode($url)
    {
        return filter_var($url, FILTER_SANITIZE_ENCODED);
    }

    /**
     * @param $string
     * @return string
     *
     * Formats a string into a valid name format.
     * (Controversial, but correct the vast majority of the time. Your mileage may vary.)
     *
     * The logic is as follows:
     * keeps only letters, - and ';
     * removes double occurrences of -;
     * removes double occurrences of ';
     * removes extra white space;
     * trims whitespace, - and ' from the beginning and end of string;
     * Ensures all portions of a name are the correct case.
     */
    public static function cleanseName($string)
    {
        $string = preg_replace('/[^\s\p{L}\'-]/u', '', $string);
        $string = preg_replace("/(-)\\1+/", "$1", $string);
        $string = preg_replace("/(')\\1+/", "$1", $string);
        $string = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
        $string = trim($string, "-' \t\n");
        $string = ucwords(strtolower($string));

        foreach (array('-', '\'') as $delimiter) {
            if (strpos($string, $delimiter) !== false) {
                $string = implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
            }
        }

        return $string;
    }

    /**
     * Removes any "noise words" from a search query and returns the remaining request.
     * Noise words can be too general and muddy the search.
     * As such, it can be better to dig down to the real keywords.
     *
     * @param $searchString
     * @return array
     */
    public static function removeNoiseWords($searchString)
    {
        $words = explode(' ', $searchString);

        $acceptedWords = array();

        $noiseWords = static::$noiseWords;

        foreach ($words as $word) {
            $word = strtolower(trim($word));
            $word = str_replace('?', '', $word);

            if (!in_array($word, $noiseWords)) {
                array_push($acceptedWords, $word);
            }
        }

        return $acceptedWords;
    }

    /**
     * Removes all punctuation from a string and returns what's left.
     *
     * @param $string
     * @return mixed
     */
    public static function removePunctuation($string)
    {
        return preg_replace('/[.,?!\'\"£€$:;()&%=-]?/', '', $string);
    }
}

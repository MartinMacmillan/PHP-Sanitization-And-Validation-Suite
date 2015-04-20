<?php

require_once dirname(__FILE__).'/../src/classes/inputSanitizer.php';

class inputSanitizerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_provides_helper_methods_for_sanitization()
    {
        new InputSanitizer;
    }

    /**
     * @test
     */
    public function it_converts_html_from_a_string_into_entities()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals('&lt;script src=&quot;evil-script&quot;&gt;&lt;/script&gt;', $sanitizer->cleanXss('<script src="evil-script"></script>'));
        $this->assertEquals('Hello, there!', $sanitizer->cleanXss('Hello, there!'));
    }

    /**
     * @test
     */
    public function it_transforms_input_into_an_integer()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals(5, $sanitizer->sanitizeInteger('5'));
        $this->assertEquals(0, $sanitizer->sanitizeInteger('string'));
    }

    /**
     * @test
     */
    public function it_transforms_input_into_a_float()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals(5, $sanitizer->sanitizeNumber('5'));
        $this->assertEquals(6.78910, $sanitizer->sanitizeNumber('6.78910'));
        $this->assertEquals(33.3, $sanitizer->sanitizeNumber('33.3randomstring'));
        $this->assertEquals(0, $sanitizer->sanitizeNumber('string'));
    }

    /**
     * @test
     */
    public function it_removes_all_html_characters_from_string()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals('Hello, World', $sanitizer->sanitizeString('<h1>Hello, World</h1>'));
        $this->assertEquals('', $sanitizer->sanitizeString('<script src="evil-script"></script>'));
    }

    /**
     * @test
     */
    public function it_url_encodes_a_string()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals(
            'http%3A%2F%2Fwww.example.com%2Fexample-directory%2Fexamplefile.html',
            $sanitizer->urlEncode('http://www.example.com/example-directory/examplefile.html')
        );
    }

    /**
     * @test
     */
    public function it_sanitizes_an_email()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals('abc@example.com', $sanitizer->sanitizeEmail('abc@example.com'));
        $this->assertEquals('thisisnotallowed@example.com', $sanitizer->sanitizeEmail('this\ is\"not\\allowed@example.com'));
    }

    /**
     * @test
     */
    public function it_converts_a_name_into_a_valid_format()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals('John Smith', $sanitizer->cleanseName('John Smith'));
        $this->assertEquals('Benjamin O\'Shea', $sanitizer->cleanseName('Benjamin o\'shea'));
        $this->assertEquals('Shaun C Wright-Phillips', $sanitizer->cleanseName('shaun c wright-phillips'));
    }

    /**
     * @test
     */
    public function it_strips_out_noise_words_from_a_string()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals(array('top', '10', 'fashion', 'trends', '2015'), $sanitizer->removeNoiseWords('Top 10 fashion trends 2015'));
        $this->assertEquals(array('top', '10', 'fashion', 'trends', '2015'), $sanitizer->removeNoiseWords('What are the top 10 fashion trends of 2015?'));
    }

    /**
     * @test
     */
    public function it_strips_out_all_punctuation_from_a_string()
    {
        $sanitizer = new InputSanitizer;

        $this->assertEquals('Hello Im Martin', $sanitizer->removePunctuation('Hello, I\'m Martin.'));
    }
}

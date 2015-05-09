<?php

require_once __DIR__.'/../src/inputValidator.php';

class inputValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_returns_true_on_valid_email()
    {
        $validator = new InputValidator;

        $this->assertEquals(true, $validator->validateEmail('abc@example.com'));
        $this->assertEquals(false, $validator->validateEmail('abc"example.com'));
        $this->assertEquals(
            false,
            $validator->validateEmail('abcedefghijklmnopqrstuvwxyz1234567890thisistoolongforanemailaddress@abcdefghijklmnoprstuvwxyz1234567890.com')
        );
    }

    /**
     * @test
     */
    public function it_returns_true_on_valid_text_input()
    {
        $validator = new InputValidator;

        $this->assertEquals(true, $validator->validateText('Hello, World'));
        $this->assertEquals(false, $validator->validateEmail(''));
    }

    /**
     * @test
     */
    public function it_returns_true_on_valid_number()
    {
        $validator = new InputValidator;

        $this->assertEquals(true, $validator->validateNumber(123));
        $this->assertEquals(false, $validator->validateNumber('abc'));
    }

    /**
     * @test
     */
    public function it_returns_true_on_valid_full_uk_postcode()
    {
        $validator = new InputValidator;

        $this->assertEquals(true, $validator->validateFullUKPostcode('NN4 7EB'));
        $this->assertEquals(true, $validator->validateFullUKPostcode('SW162AB'));
        $this->assertEquals(true, $validator->validateFullUKPostcode('sw16 2ab'));
        $this->assertEquals(false, $validator->validateFullUKPostcode('SW16 AAB'));
    }

    /**
     * @test
     */
    public function it_returns_true_on_partial_and_full_uk_postcode()
    {
        $validator = new InputValidator;

        $this->assertEquals(true, $validator->validatePartialUKPostcode('NN4'));
        $this->assertEquals(true, $validator->validatePartialUKPostcode('SW16'));
        $this->assertEquals(true, $validator->validatePartialUKPostcode('sw16 2ab'));
        $this->assertEquals(false, $validator->validatePartialUKPostcode('3NA'));
    }

    /**
     * @test
     */
    public function it_returns_true_on_valid_uk_telephone()
    {
        $validator = new InputValidator;

        $this->assertEquals(true, $validator->validateUKTel('01234 567890'));
        $this->assertEquals(true, $validator->validateUKTel('+441234 567890'));
        $this->assertEquals(true, $validator->validateUKTel('0441234 567890'));
        $this->assertEquals(true, $validator->validateUKTel('00441234 567890'));
        $this->assertEquals(false, $validator->validateUKTel('0451234 567890'));
    }
}

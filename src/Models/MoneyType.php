<?php 
namespace Pruiti\AmazonMWS\Models;

class MoneyType {

    public $Amount;
    public $CurrencyCode;

    private $validation_errors = [];

    public function __construct(array $array = [])
    {
        foreach ($array as $property => $value) {
            $this->{$property} = $value;
        }
    }
    
    public function getValidationErrors()
    {
        return $this->validation_errors;   
    }
    
    public function toArray()
    {
        return [
            'Amount' => $this->Amount,
            'CurrencyCode' => $this->CurrencyCode,
        ];
    }
    
    public function validate()
    {
        if (count($this->validation_errors) > 0) {
            return false;    
        } else {
            return true;    
        }
    }
    
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }    
}

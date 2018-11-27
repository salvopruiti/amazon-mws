<?php 
namespace SalvatorePruiti\AmazonMWS\Models;

class PriceToEstimateFees {

    /** @var  MoneyType */
    public $ListingPrice;
    /** @var  MoneyType */
    public $Shipping;

    /** @var  Points */
    public $Points;

    private $validation_errors = [];

    public function __construct(array $array = [])
    {
        $this->ListingPrice = new MoneyType();
        $this->Shipping = new MoneyType();
        $this->Points = new Points();

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
            'ListingPrice' => $this->ListingPrice->toArray(),
            'Shipping' => $this->Shipping->toArray(),
            'Points' => $this->Points->toArray(),
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

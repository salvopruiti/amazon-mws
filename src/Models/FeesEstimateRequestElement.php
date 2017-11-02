<?php 
namespace Pruiti\AmazonMWS\Models;

class FeesEstimateRequestElement {

    public $MarketplaceId;
    public $IdType = 'ASIN';
    public $IdValue;
    /** @var  PriceToEstimateFees */
    public $PriceToEstimateFees;
    public $Identifier;
    public $IsAmazonFulfilled = false;

    private $validation_errors = [];

    public function __construct(array $array = [])
    {
        $this->PriceToEstimateFees = new PriceToEstimateFees();
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
            'MarketplaceId' => $this->MarketplaceId,
            'IdType' => $this->IdType,
            'IdValue' => $this->IdValue,
            'PriceToEstimateFees' => $this->PriceToEstimateFees->toArray(),
            'Identifier' => $this->Identifier,
        ];
    }
    
    public function validate()
    {
        if (mb_strlen($this->sku) < 1 or strlen($this->sku) > 40) {
            $this->validation_errors['sku'] = 'Should be longer then 1 character and shorter then 40 characters';
        }
        
        $this->price = str_replace(',', '.', $this->price);
        
        $exploded_price = explode('.', $this->price);
        
        if (count($exploded_price) == 2) {
            if (mb_strlen($exploded_price[0]) > 18) { 
                $this->validation_errors['price'] = 'Too high';        
            } else if (mb_strlen($exploded_price[1]) > 2) {
                $this->validation_errors['price'] = 'Too many decimals';    
            }
        } else {
            $this->validation_errors['price'] = 'Looks wrong';        
        }
        
        $this->quantity = (int) $this->quantity;
        $this->product_id = (string) $this->product_id;
        
        $product_id_length = mb_strlen($this->product_id);
        
        switch ($this->product_id_type) {
            case 'ASIN':
                if ($product_id_length != 10) {
                    $this->validation_errors['product_id'] = 'ASIN should be 10 characters long';                
                }
                break;
            case 'UPC':
                if ($product_id_length != 12) {
                    $this->validation_errors['product_id'] = 'UPC should be 12 characters long';                
                }
                break;
            case 'EAN':
                if ($product_id_length != 13) {
                    $this->validation_errors['product_id'] = 'EAN should be 13 characters long';                
                }
                break;
            default:
               $this->validation_errors['product_id_type'] = 'Not one of: ASIN,UPC,EAN';        
        }
        
        if (!in_array($this->condition_type, $this->conditions)) {
            $this->validation_errors['condition_type'] = 'Not one of: ' . implode($this->conditions, ',');                
        }
        
        if ($this->condition_type != 'New') {
            $length = mb_strlen($this->condition_note);
            if ($length < 1) {
                $this->validation_errors['condition_note'] = 'Required if condition_type not is New';                    
            } else if ($length > 1000) {
                $this->validation_errors['condition_note'] = 'Should not exceed 1000 characters';                    
            }
        }

        if($this->handling_time && !is_numeric($this->handling_time))
            $this->validation_errors['handling_time'] = 'Handling Time must be a number';

        if ($this->add_delete && !in_array($this->add_delete, ['a','d','x']))
            $this->validation_errors['add_delete'] = 'Invalid Add Mode. Valids: a => update/add, d => delete offer, x => remove product';

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

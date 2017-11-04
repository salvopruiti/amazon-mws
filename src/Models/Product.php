<?php 
namespace Pruiti\AmazonMWS\Models;

class Product {

    const ADDMODE_ADD_UPDATE = 'a';
    const ADDMODE_DELETE = 'd';
    const ADDMODE_REMOVE = 'x';

    public $sku;
    public $price;
    public $quantity = 0;
    public $product_id;
    public $product_id_type;
    public $condition_type = 'New';
    public $condition_note;
    public $add_delete = "a";
    public $handling_time;
    
    private $validation_errors = [];
    
    private $conditions = [
        'New', 'Refurbished', 'UsedLikeNew', 
        'UsedVeryGood', 'UsedGood', 'UsedAcceptable'
    ];
    
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
            'sku' => $this->sku,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'product_id' => $this->product_id,
            'product_id_type' => $this->product_id_type,
            'condition_type' => $this->condition_type,
            'condition_note' => $this->condition_note,
            'add_delete' => $this->add_delete,
            'handling_time' => $this->handling_time,
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
    
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public function __set($property, $value) {
        $this->$property = $value;
    }
}

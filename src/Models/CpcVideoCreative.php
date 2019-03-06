<?php 
namespace YandexDirectSDK\Models; 

use YandexDirectSDK\Components\Model;

/** 
 * Class CpcVideoCreative 
 * 
 * @property-read   integer   $duration 
 * 
 * @method          integer   getDuration() 
 * 
 * @package YandexDirectSDK\Models 
 */ 
class CpcVideoCreative extends Model 
{ 
    protected $compatibleCollection; 

    protected $serviceProvidersMethods = [];

    protected $properties = [
        'duration' => 'integer'
    ];

    protected $nonWritableProperties = [
        'duration'
    ];

    protected $nonReadableProperties = []; 

    protected $requiredProperties = []; 
}
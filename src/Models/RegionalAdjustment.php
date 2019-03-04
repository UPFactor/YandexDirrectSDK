<?php 
namespace YandexDirectSDK\Models; 

use YandexDirectSDK\Collections\RegionalAdjustments;
use YandexDirectSDK\Components\Model;

/** 
 * Class RegionalAdjustment 
 * 
 * @property        integer   $regionId 
 * @property        integer   $bidModifier 
 * @property-read   string    $enabled 
 * 
 * @method          $this     setRegionId(integer $regionId) 
 * @method          $this     setBidModifier(integer $bidModifier) 
 * 
 * @method          integer   getRegionId() 
 * @method          integer   getBidModifier() 
 * @method          string    getEnabled() 
 * 
 * @package YandexDirectSDK\Models 
 */ 
class RegionalAdjustment extends Model 
{ 
    protected $compatibleCollection = RegionalAdjustments::class;

    protected $serviceProvidersMethods = []; 

    protected $properties = [
        'regionId' => 'integer',
        'bidModifier' => 'integer',
        'enabled' => 'string'
    ];

    protected $nonWritableProperties = [
        'enabled'
    ];

    protected $nonReadableProperties = []; 

    protected $requiredProperties = [
        'regionId',
        'bidModifier'
    ];
}
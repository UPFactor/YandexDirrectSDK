<?php 
namespace YandexDirectSDK\Collections; 

use YandexDirectSDK\Components\QueryBuilder; 
use YandexDirectSDK\Components\ModelCollection; 
use YandexDirectSDK\Models\Creative;
use YandexDirectSDK\Services\CreativesService;

/** 
 * Class Creatives 
 * 
 * @method   QueryBuilder   query() 
 * 
 * @package YandexDirectSDK\Collections 
 */ 
class Creatives extends ModelCollection 
{ 
    /** 
     * @var Creative[] 
     */ 
    protected $items = []; 

    /** 
     * @var Creative 
     */ 
    protected $compatibleModel = Creative::class;

    protected $serviceProvidersMethods = [
        'query' => CreativesService::class
    ];
}
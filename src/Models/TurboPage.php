<?php 
namespace YandexDirectSDK\Models; 

use YandexDirectSDK\Collections\TurboPages;
use YandexDirectSDK\Components\Model;
use YandexDirectSDK\Components\QueryBuilder;
use YandexDirectSDK\Services\TurboPagesService;

/** 
 * Class TurboPage 
 * 
 * @property-readable   integer        $id
 * @property-readable   string         $name
 * @property-readable   string         $href
 * @property-readable   string         $previewHref
 * 
 * @method              QueryBuilder   query()
 * 
 * @method              integer        getId()
 * @method              string         getName()
 * @method              string         getHref()
 * @method              string         getPreviewHref()
 * 
 * @package YandexDirectSDK\Models 
 */ 
class TurboPage extends Model 
{ 
    protected $compatibleCollection = TurboPages::class;

    protected $serviceProvidersMethods = [
        'query' => TurboPagesService::class,
    ];

    protected $properties = [
        'id' => 'integer',
        'name' => 'string',
        'href' => 'string',
        'previewHref' => 'string'
    ];

    protected $nonWritableProperties = [
        'id',
        'name',
        'href',
        'previewHref'
    ];

    protected $nonReadableProperties = [];
}
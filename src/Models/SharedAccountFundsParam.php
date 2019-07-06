<?php 
namespace YandexDirectSDK\Models; 

use YandexDirectSDK\Components\Model as Model; 

/** 
 * Class SharedAccountFundsParam 
 * 
 * @property-readable   integer   $refund
 * @property-readable   integer   $spend
 * 
 * @method              integer   getRefund()
 * @method              integer   getSpend()
 * 
 * @package YandexDirectSDK\Models 
 */ 
class SharedAccountFundsParam extends Model 
{ 
    protected static $properties = [
        'refund' => 'integer',
        'spend' => 'integer'
    ];

    protected static $nonWritableProperties = [
        'refund',
        'spend'
    ];
}
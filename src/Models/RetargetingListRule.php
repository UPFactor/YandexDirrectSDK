<?php 
namespace YandexDirectSDK\Models; 

use YandexDirectSDK\Collections\RetargetingListRuleArguments;
use YandexDirectSDK\Collections\RetargetingListRules;
use YandexDirectSDK\Components\Model;

/** 
 * Class RetargetingListRule 
 * 
 * @property   RetargetingListRuleArguments   $arguments 
 * @property   string                         $operator 
 * 
 * @method     $this                          setArguments(RetargetingListRuleArguments $arguments) 
 * @method     $this                          setOperator(string $operator) 
 * 
 * @method     RetargetingListRuleArguments   getArguments() 
 * @method     string                         getOperator() 
 * 
 * @package YandexDirectSDK\Models 
 */ 
class RetargetingListRule extends Model 
{ 
    const ALL = 'ALL';
    const ANY = 'ANY';
    const NONE = 'NONE';

    protected $compatibleCollection = RetargetingListRules::class;

    protected $serviceProvidersMethods = []; 

    protected $properties = [
        'arguments' => 'object:' . RetargetingListRuleArguments::class,
        'operator' => 'enum:' . self::ALL . ',' . self::ANY . ',' . self::NONE,
    ];

    protected $nonWritableProperties = []; 

    protected $nonReadableProperties = []; 

    protected $requiredProperties = [
        'arguments',
        'operator'
    ];
}
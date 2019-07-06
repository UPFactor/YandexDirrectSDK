<?php 
namespace YandexDirectSDK\Models; 

use YandexDirectSDK\Collections\AudienceTargetBids;
use YandexDirectSDK\Components\Model;

/** 
 * Class AudienceTargetBid 
 * 
 * @property          integer   $id
 * @property          integer   $adGroupId
 * @property          integer   $campaignId
 * @property          integer   $contextBid
 * @property          string    $strategyPriority
 * 
 * @method            $this     setId(integer $id)
 * @method            $this     setAdGroupId(integer $adGroupId)
 * @method            $this     setCampaignId(integer $campaignId)
 * @method            $this     setContextBid(integer $contextBid)
 * @method            $this     setStrategyPriority(string $strategyPriority)
 * 
 * @method            integer   getId()
 * @method            integer   getAdGroupId()
 * @method            integer   getCampaignId()
 * @method            integer   getContextBid()
 * @method            string    getStrategyPriority()
 * 
 * @package YandexDirectSDK\Models 
 */ 
class AudienceTargetBid extends Model 
{
    const LOW = 'LOW';
    const NORMAL = 'NORMAL';
    const HIGH = 'HIGH';

    protected static $compatibleCollection = AudienceTargetBids::class;

    protected static $properties = [
        'id' => 'integer',
        'adGroupId' => 'integer',
        'campaignId' => 'integer',
        'contextBid' => 'integer',
        'strategyPriority' => 'enum:' . self::LOW . ',' . self::NORMAL . ',' . self::HIGH,
    ];

    protected static $nonAddableProperties = [
        'id'
    ];
}
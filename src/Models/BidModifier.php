<?php 
namespace YandexDirectSDK\Models; 

use YandexDirectSDK\Collections\BidModifiers;
use YandexDirectSDK\Components\Model;
use YandexDirectSDK\Components\Result; 
use YandexDirectSDK\Components\QueryBuilder;
use YandexDirectSDK\Models\Foundation\To;
use YandexDirectSDK\Services\BidModifiersService;

/** 
 * Class BidModifier 
 * 
 * @property          integer                    $id
 * @property          integer                    $campaignId
 * @property          integer                    $adGroupId
 * @property          MobileAdjustment           $mobileAdjustment
 * @property          DesktopAdjustment          $desktopAdjustment
 * @property          DemographicsAdjustment     $demographicsAdjustment
 * @property          RetargetingAdjustment      $retargetingAdjustment
 * @property          RegionalAdjustment         $regionalAdjustment
 * @property          VideoAdjustment            $videoAdjustment
 * @property-read     string                     $level
 * @property-read     string                     $type
 *                                               
 * @method static     QueryBuilder               query()
 * @method            Result                     create()
 * @method            Result                     delete()
 * @method            Result                     applyCoefficient(int $coefficient=null)
 * @method            $this                      setId(integer $id)
 * @method            integer                    getId()
 * @method            $this                      setCampaignId(integer $campaignId)
 * @method            integer                    getCampaignId()
 * @method            $this                      setAdGroupId(integer $adGroupId)
 * @method            integer                    getAdGroupId()
 * @method            $this                      setMobileAdjustment(MobileAdjustment|array $mobileAdjustment)
 * @method            MobileAdjustment           getMobileAdjustment()
 * @method            $this                      setDesktopAdjustment(DesktopAdjustment|array $desktopAdjustment)
 * @method            DesktopAdjustment          getDesktopAdjustment()
 * @method            $this                      setDemographicsAdjustment(DemographicsAdjustment|array $demographicsAdjustment)
 * @method            DemographicsAdjustment     getDemographicsAdjustment()
 * @method            $this                      setRetargetingAdjustment(RetargetingAdjustment|array $retargetingAdjustment)
 * @method            RetargetingAdjustment      getRetargetingAdjustment()
 * @method            $this                      setRegionalAdjustment(RegionalAdjustment|array $regionalAdjustment)
 * @method            RegionalAdjustment         getRegionalAdjustment()
 * @method            $this                      setVideoAdjustment(VideoAdjustment|array $videoAdjustment)
 * @method            VideoAdjustment            getVideoAdjustment()
 * @method            string                     getLevel()
 * @method            string                     getType()
 * 
 * @package YandexDirectSDK\Models 
 */ 
class BidModifier extends Model 
{
    use To;

    protected static $compatibleCollection = BidModifiers::class;

    protected static $staticMethods = [
        'query' => BidModifiersService::class
    ];

    protected static $methods = [
        'create' => BidModifiersService::class,
        'delete' => BidModifiersService::class,
        'applyCoefficient' => BidModifiersService::class
    ];

    protected static $properties = [
        'id' => 'integer',
        'campaignId' => 'integer',
        'adGroupId' => 'integer',
        'mobileAdjustment' => 'object:' . MobileAdjustment::class,
        'desktopAdjustment' => 'object:' . DesktopAdjustment::class,
        'demographicsAdjustment' => 'object:' . DemographicsAdjustment::class,
        'retargetingAdjustment' => 'object:' . RetargetingAdjustment::class,
        'regionalAdjustment' => 'object:' . RegionalAdjustment::class,
        'videoAdjustment' => 'object:' . VideoAdjustment::class,
        'level' => 'string',
        'type' => 'string'
    ];

    protected static $nonWritableProperties = [
        'level',
        'type'
    ];

    protected static $nonAddableProperties = [
        'id'
    ];
}
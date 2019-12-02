<?php 
namespace YandexDirectSDK\Models; 

use YandexDirectSDK\Collections\BidModifierSets;
use YandexDirectSDK\Components\Model;
use YandexDirectSDK\Components\Result;
use YandexDirectSDK\Services\BidModifiersService;

/** 
 * Class BidModifierSet 
 * 
 * @property     integer     $id
 * @property     integer     $bidModifier
 *                           
 * @method       $this       setId(integer $id)
 * @method       integer     getId()
 * @method       $this       setBidModifier(integer $bidModifier)
 * @method       integer     getBidModifier()
 * 
 * @package YandexDirectSDK\Models 
 */ 
class BidModifierSet extends Model 
{ 
    protected static $compatibleCollection = BidModifierSets::class;

    protected static $properties = [
        'id' => 'integer',
        'bidModifier' => 'integer'
    ];

    public function apply():Result
    {
        return BidModifiersService::applyCoefficient($this);
    }
}
<?php

namespace YandexDirectSDK\Services;

use YandexDirectSDK\Collections\Bids;
use YandexDirectSDK\Collections\BidsAuto;
use YandexDirectSDK\Collections\KeywordBids;
use YandexDirectSDK\Collections\KeywordBidsAuto;
use YandexDirectSDK\Collections\Keywords;
use YandexDirectSDK\Components\Service;
use YandexDirectSDK\Components\Result;
use YandexDirectSDK\Components\QueryBuilder;
use YandexDirectSDK\Exceptions\InvalidArgumentException;
use YandexDirectSDK\Exceptions\RequestException;
use YandexDirectSDK\Exceptions\RuntimeException;
use YandexDirectSDK\Exceptions\ServiceException;
use YandexDirectSDK\Interfaces\ModelCommon as ModelCommonInterface;
use YandexDirectSDK\Models\Bid;
use YandexDirectSDK\Models\BidAuto;
use YandexDirectSDK\Models\Keyword;
use YandexDirectSDK\Models\KeywordBid;
use YandexDirectSDK\Models\KeywordBidAuto;

/** 
 * Class KeywordsService 
 * 
 * @method   Result         add(ModelCommonInterface $keywords)
 * @method   Result         delete(ModelCommonInterface|integer[]|integer $keywords)
 * @method   QueryBuilder   query() 
 * @method   Result         resume(ModelCommonInterface|integer[]|integer $keywords)
 * @method   Result         suspend(ModelCommonInterface|integer[]|integer $keywords)
 * @method   Result         update(ModelCommonInterface $keywords)
 * 
 * @package YandexDirectSDK\Services 
 */
class KeywordsService extends Service
{
    protected $serviceName = 'keywords';

    protected $serviceModelClass = Keyword::class;

    protected $serviceModelCollectionClass = Keywords::class;

    protected $serviceMethods = [
        'add' => 'add:addCollection',
        'delete' => 'delete:actionByIds',
        'query' => 'get:selectionElements',
        'resume' => 'resume:actionByIds',
        'suspend' => 'suspend:actionByIds',
        'update' => 'update:updateCollection',
    ];

    /**
     * Set bids for given keywords.
     *
     * @param integer|integer[]|Keyword|Keywords|ModelCommonInterface $keywords
     * @param integer $bid
     * @param integer|null $contextBid
     * @return Result
     * @throws InvalidArgumentException
     */
    public function updateBids($keywords, $bid = null, $contextBid = null): Result
    {
        $collection = new Bids();

        foreach ($this->extractIds($keywords) as $id){
            $model = Bid::make()->setKeywordId($id);
            if (!is_null($contextBid)) $model->setBid((integer) $bid);
            if (!is_null($contextBid)) $model->setContextBid((integer) $contextBid);
            $collection->push($model);
        }

        return $this->session->getBidsService()->set($collection);
    }

    /**
     * Set strategy priority for given keywords.
     *
     * @param integer|integer[]|Keyword|Keywords|ModelCommonInterface $keywords
     * @param string $strategyPriority
     * @return Result
     * @throws InvalidArgumentException
     */
    public function updateStrategyPriority($keywords, string $strategyPriority): Result
    {
        $collection = new Bids();

        foreach ($this->extractIds($keywords) as $id){
            $collection->push(
                Bid::make()
                    ->setKeywordId($id)
                    ->setStrategyPriority($strategyPriority)
            );
        }

        return $this->session->getBidsService()->set($collection);
    }

    /**
     * Sets bid designer options for given keywords.
     *
     * @param integer|integer[]|Keyword|Keywords|ModelCommonInterface $keywords
     * @param BidAuto|BidsAuto|ModelCommonInterface $bidsAuto
     * @return Result
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws RuntimeException
     * @throws ServiceException
     */
    public function updateBidsAuto($keywords, ModelCommonInterface $bidsAuto): Result
    {
        return $this->session->getBidsService()->setAuto(
            $this->bind($keywords, $bidsAuto, 'KeywordId')
        );
    }

    /**
     * Gets bids for given keywords.
     *
     * @param integer|integer[]|Keyword|Keywords|ModelCommonInterface $keywords
     * @param array $fields
     * @return Result
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function getRelatedBids($keywords, array $fields): Result
    {
        return $this->session->getBidsService()->query()
            ->select($fields)
            ->whereIn('KeywordIds', $this->extractIds($keywords))
            ->get();
    }

    /**
     * Set keyword bids.
     *
     * @param integer|integer[]|Keyword|Keywords|ModelCommonInterface $keywords
     * @param integer $searchBid
     * @param integer|null $networkBid
     * @return Result
     * @throws InvalidArgumentException
     */
    public function updateKeywordBids($keywords, $searchBid = null, $networkBid = null): Result
    {
        $collection = new KeywordBids();

        foreach ($this->extractIds($keywords) as $id){
            $model = KeywordBid::make()->setKeywordId($id);
            if (!is_null($networkBid)) $model->setSearchBid((integer) $searchBid);
            if (!is_null($networkBid)) $model->setNetworkBid((integer) $networkBid);
            $collection->push($model);
        }

        return $this->session->getKeywordBidsService()->set($collection);
    }

    /**
     * Set keyword strategy priority.
     *
     * @param integer|integer[]|Keyword|Keywords|ModelCommonInterface $keywords
     * @param string $strategyPriority
     * @return Result
     * @throws InvalidArgumentException
     */
    public function updateKeywordStrategyPriority($keywords, string $strategyPriority): Result
    {
        $collection = new KeywordBids();

        foreach ($this->extractIds($keywords) as $id){
            $model = KeywordBid::make()
                ->setKeywordId($id)
                ->setNetworkBid($strategyPriority);

            $collection->push($model);
        }

        return $this->session->getKeywordBidsService()->set($collection);
    }

    /**
     * Set keyword bid designer options.
     *
     * @param integer|integer[]|Keyword|Keywords|ModelCommonInterface $keywords
     * @param KeywordBidAuto|KeywordBidsAuto|ModelCommonInterface $keywordsBidsAuto
     * @return Result
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws RuntimeException
     * @throws ServiceException
     */
    public function updateKeywordBidsAuto($keywords, ModelCommonInterface $keywordsBidsAuto): Result
    {
        return $this->session->getKeywordBidsService()->setAuto(
            $this->bind($keywords, $keywordsBidsAuto, 'KeywordId')
        );
    }

    /**
     * Gets keyword bids.
     *
     * @param integer|integer[]|Keyword|Keywords|ModelCommonInterface $keywords
     * @param array $fields
     * @return Result
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function getRelatedKeywordBids($keywords, array $fields): Result
    {
        return $this->session->getKeywordBidsService()->query()
            ->select($fields)
            ->whereIn('KeywordIds', $this->extractIds($keywords))
            ->get();
    }
}
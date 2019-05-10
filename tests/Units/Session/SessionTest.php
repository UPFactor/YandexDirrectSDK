<?php

namespace YandexDirectSDKTest\Unit\Session;

use PHPUnit\Framework\TestCase;
use YandexDirectSDK\Components\Result;
use YandexDirectSDK\Exceptions\InvalidArgumentException;
use YandexDirectSDK\Exceptions\RequestException;
use YandexDirectSDK\Exceptions\RuntimeException;
use YandexDirectSDK\Services\AdExtensionsService;
use YandexDirectSDK\Services\AdGroupsService;
use YandexDirectSDK\Services\AdImagesService;
use YandexDirectSDK\Services\AdsService;
use YandexDirectSDK\Services\AgencyClientsService;
use YandexDirectSDK\Services\AudienceTargetsService;
use YandexDirectSDK\Services\BidModifiersService;
use YandexDirectSDK\Services\BidsService;
use YandexDirectSDK\Services\CampaignsService;
use YandexDirectSDK\Services\ChangesService;
use YandexDirectSDK\Services\ClientsService;
use YandexDirectSDK\Services\DictionariesService;
use YandexDirectSDK\Services\DynamicTextAdTargetsService;
use YandexDirectSDK\Services\KeywordBidsService;
use YandexDirectSDK\Services\KeywordsResearchService;
use YandexDirectSDK\Services\KeywordsService;
use YandexDirectSDK\Services\LeadsService;
use YandexDirectSDK\Services\ReportsService;
use YandexDirectSDK\Services\RetargetingListsService;
use YandexDirectSDK\Services\SitelinksService;
use YandexDirectSDK\Services\VCardsService;
use YandexDirectSDK\Session;
use YandexDirectSDKTest\Helpers\SessionTools;

class SessionTest extends TestCase
{
    public $logFile = __DIR__ . '/../../Run/testConstructLogFile.log';

    public function sessionDataProvider(){

        $initData = SessionTools::getInitData();

        return [
            [
                $initData['token'],
                [
                    'client' => 'clientLogin',
                    'language' => 'ru',
                    'sandbox' => true,
                    'operatorUnits' => false,
                    'logFile' => $this->logFile
                ]
            ]
        ];
    }

    public function setUp(): void
    {
        if (file_exists($this->logFile)){
            unlink($this->logFile);
        }
    }

    /**
     * @dataProvider sessionDataProvider
     *
     * @param string $token
     * @param array $options
     * @throws RuntimeException
     */
    public function testConstruct(string $token, array $options):void
    {

        //General method of creating a session instance.

        $session = (new Session($token))
            ->setClient($options['client'])
            ->setLanguage($options['language'])
            ->useSandbox($options['sandbox'])
            ->useOperatorUnits($options['operatorUnits'])
            ->useLogFile(true, $options['logFile']);

        $expected = $options;
        $expected['token'] = $token;
        $expected['logFile'] = realpath($expected['logFile']);

        $this->assertEquals($expected, $session->fetch());
        $this->assertFileExists($expected['logFile']);

        //General method of creating an instance of a session
        //using an array of options.

        $session = new Session($token, $options);
        $this->assertEquals($expected, $session->fetch());

        //Alternate method of creating an instance of a session.

        $session = Session::make($token)
            ->setClient($options['client'])
            ->setLanguage($options['language'])
            ->useSandbox($options['sandbox'])
            ->useOperatorUnits($options['operatorUnits'])
            ->useLogFile(true, $options['logFile']);

        $this->assertEquals($expected, $session->fetch());

        //Alternate method of creating an instance of a session
        //using an array of options.

        $session = Session::make($token, $options);
        $this->assertEquals($expected, $session->fetch());
    }

    /**
     * Availability of basic services.
     *
     * @depends testConstruct
     *
     * @return void
     */
    public function testServiceAccess(): void
    {
        $session = SessionTools::init();

        $this->assertTrue($session->getCampaignsService() instanceof CampaignsService);
        $this->assertTrue($session->getAdGroupsService() instanceof AdGroupsService);
        $this->assertTrue($session->getAdsService() instanceof AdsService);
        $this->assertTrue($session->getKeywordsService() instanceof KeywordsService);
        $this->assertTrue($session->getBidsService() instanceof BidsService);
        $this->assertTrue($session->getKeywordBidsService() instanceof KeywordBidsService);
        $this->assertTrue($session->getBidModifiersService() instanceof BidModifiersService);
        $this->assertTrue($session->getAudienceTargetsService() instanceof AudienceTargetsService);
        $this->assertTrue($session->getRetargetingListsService() instanceof RetargetingListsService);
        $this->assertTrue($session->getVCardsService() instanceof VCardsService);
        $this->assertTrue($session->getSitelinksService() instanceof SitelinksService);
        $this->assertTrue($session->getAdImagesService() instanceof AdImagesService);
        $this->assertTrue($session->getAdExtensionsService() instanceof AdExtensionsService);
        $this->assertTrue($session->getDynamicTextAdTargetsService() instanceof DynamicTextAdTargetsService);
        $this->assertTrue($session->getChangesService() instanceof ChangesService);
        $this->assertTrue($session->getDictionariesService() instanceof DictionariesService);
        $this->assertTrue($session->getClientsService() instanceof ClientsService);
        $this->assertTrue($session->getAgencyClientsService() instanceof AgencyClientsService);
        $this->assertTrue($session->getKeywordsResearchService() instanceof KeywordsResearchService);
        $this->assertTrue($session->getLeadsService() instanceof LeadsService);
        $this->assertTrue($session->getReportsService('report') instanceof ReportsService);
    }

    /**
     * Calling the Yandex.Direct API method.
     *
     * @depends testConstruct
     *
     * @return void
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws RuntimeException
     */
    public function testApiCall(): void
    {
        $result = SessionTools::init()->call('dictionaries', 'get', [
            'DictionaryNames' => ['Currencies']
        ]);

        $this->assertTrue($result instanceof Result);
    }

    /**
     * Yandex.Direct API method exception: Bad Request.
     *
     * @depends testConstruct
     *
     * @return void
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws RuntimeException
     */
    public function testApiCall_RequestException_BadRequest():void
    {
        $this->expectException(RequestException::class);
        SessionTools::init()->call('dictionaries', 'get', []);
    }

    /**
     * Yandex.Direct API method exception: Not Found.
     *
     * @depends testConstruct
     *
     * @return void
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws RuntimeException
     */
    public function testApiCall_RequestException_NotFound():void
    {
        $this->expectException(RequestException::class);
        $this->expectExceptionCode(404);
        SessionTools::init()->call('', '');
    }

    /**
     * Yandex.Direct API method exception: URI Too Long.
     *
     * @depends testConstruct
     *
     * @return void
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws RuntimeException
     */
    public function testApiCall_RequestException_URITooLong():void
    {
        $this->expectException(RequestException::class);
        $this->expectExceptionCode(414);
        SessionTools::init()->call(str_repeat('dictionaries', 1000), '');
    }

    /**
     * Yandex.Direct API method exception: Lack of access to the log file.
     *
     * @depends testConstruct
     *
     * @return void
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws RuntimeException
     */
    public function testApiCall_RuntimeException_LogFileUnavailable():void
    {
        $this->expectException(RuntimeException::class);

        $session = SessionTools::init();
        $session->useLogFile(true, $this->logFile);

        chmod($this->logFile, 000);

        try {
            $session->call('dictionaries', 'get', [
                'DictionaryNames' => ['Currencies']
            ]);
        } catch (RuntimeException $e){
            unlink($this->logFile);
            throw $e;
        }
    }

}
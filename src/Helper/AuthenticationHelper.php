<?php

namespace LolAuthentifier\Helper;

use LolAuthentifier\Repository\LolApiRepository;
use RandomLib\Generator;
use SlimSession\Helper;

class AuthenticationHelper
{
    /**
     * @var LolApiRepository
     */
    private $lolApiRepository;

    /**
     * @var Helper
     */
    private $session;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var int
     */
    private $secretKeyLength;

    /**
     * AuthenticationHelper constructor.
     * @param LolApiRepository $lolApiRepository
     * @param Helper $session
     * @param Generator $generator
     * @param $secretKeyLength
     */
    public function __construct(LolApiRepository $lolApiRepository, Helper $session, Generator $generator, $secretKeyLength)
    {
        $this->lolApiRepository = $lolApiRepository;
        $this->session = $session;
        $this->generator = $generator;
        $this->secretKeyLength = $secretKeyLength;
    }

    /**
     * @return string
     */
    public function initializeSecretKey() : string
    {
       $secretKey = $this->generator->generateString($this->secretKeyLength);
       $this->session->set('secretKey', $secretKey);

       return $secretKey;
    }

    public function deleteSecretKey()
    {

    }

    /**
     * @param string $summonerName
     * @return bool
     */
    public function isAuthenticated(string $summonerName): bool
    {
        $summonerId = $this->lolApiRepository->getSummonerIdBySummonerName($summonerName);
        $summonerRunesPages = $this->lolApiRepository->getSummonerRunesBySummonerId($summonerId);
        $secretKey = $this->session->get('secretKey');

        if (null != $secretKey) {
            return false;
        }

        foreach ($summonerRunesPages as $summonerRunesPage) {
            if (isset($summonerRunesPage['name']) && $summonerRunesPage['name'] == $secretKey) {
                return true;
            }
        }

        return false;
    }
}
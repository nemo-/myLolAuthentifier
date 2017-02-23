<?php

namespace LolAuthentifier\Repository;

use GuzzleHttp\Client;

class LolApiRepository
{
    /**
     * @var Client
     */
    private $client;

    /**
     * LolApiRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getSummonerIdBySummonerName(string $summonerName): int
    {
        $path = sprintf('/api/lol/euw/v1.4/summoner/by-name/%s', $summonerName);

        return json_decode((string)$this->client->get($path)->getBody(), true)[str_replace(' ', '', strtolower($summonerName))]['id'];
    }

    public function getSummonerRunesBySummonerId(int $summonerId): array
    {
        $path = sprintf('/api/lol/euw/v1.4/summoner/%s/runes', $summonerId);

        return json_decode((string)$this->client->get($path)->getBody(), true)[$summonerId]['pages'];
    }
}
<?php


namespace BangerGames\SteamGameServerLoginToken;


use GuzzleHttp\Client;

class TokenService
{
    protected $interface = 'IGameServersService';

    protected $version = 'v1';

    protected $url = 'https://api.steampowered.com/';

    protected function getApiKey(): ?string
    {
        return env('STEAM_API_KEY');
    }

    private function getResponse(string $method, $requestMethod = 'GET', $arguments = [])
    {
        $client = new Client();

        $url = $this->url.$this->interface.'/'.$method.'/'.$this->version.'/';

        if ($requestMethod === 'GET') {
            $response = $client->get($url.'?'.http_build_query([
                    'key' => $this->getApiKey(),
                    'format' => 'json',
                    'input_json' => $arguments,
                ]));
        }

        if ($requestMethod === 'POST') {
            $response = $client->post($url.'?'.http_build_query([
                    'key' => $this->getApiKey(),
                    'format' => 'json',
                    'input_json' => json_encode($arguments),
                ]));
        }

        $data = $response->getBody()->getContents();

        return json_decode($data);
    }

    /**
     * @return mixed
     */
    public function getAccountList()
    {
        return $this->getResponse('GetAccountList');
    }

    /**
     * 730: csgo
     * @param  int  $appId
     * @param  string  $memo
     * @return mixed
     */
    public function createAccount(int $appId = 730, string $memo = 'bangergames')
    {
        return $this->getResponse('CreateAccount', 'POST', [
            'appId' => $appId,
            'memo' => $memo
        ]);
    }

    /**
     * @param  string  $steamId
     * @param  string  $memo
     * @return mixed
     */
    public function setMemo(string $steamId, string $memo = 'bangergames')
    {
        return $this->getResponse('SetMemo', 'POST', [
            'steamId' => $steamId,
            'memo' => $memo
        ]);
    }

    /**
     * @param  int  $steamId
     * @return mixed
     */
    public function resetLoginToken(int $steamId)
    {
        return $this->getResponse('ResetLoginToken', 'POST', [
            'steamId' => $steamId
        ]);
    }

    /**
     * @param  int  $steamId
     * @return mixed
     */
    public function deleteAccount(int $steamId)

    {
        return $this->getResponse('DeleteAccount', 'POST', [
            'steamId' => $steamId
        ]);
    }

    /**
     * @param  int  $steamId
     * @return mixed
     */
    public function getAccountPublicInfo(int $steamId)
    {
        return $this->getResponse('GetAccountPublicInfo', 'GET', [
            'steamId' => $steamId,
        ]);
    }




    /**
     * @param  string  $loginToken
     * @return mixed
     */
    public function queryLoginToken(string $loginToken)
    {
        return $this->getResponse('QueryLoginToken', 'GET', [
            'login_token' => $loginToken,
        ]);
    }

    /**
     * @param  string  $steamId
     * @param  bool  $banned
     * @param  int  $banSeconds
     * @return mixed
     */
    public function setBanStatus(string $steamId, bool $banned = false, int $banSeconds = 60)
    {
        return $this->getResponse('SetBanStatus', 'POST', [
            'steamId' => $steamId,
            'banned' => $banned,
            'ban_seconds' => $banSeconds,
        ]);
    }

    /**
     * @param  string  $serverIps
     * @return mixed
     */
    public function getServerSteamIDsByIP(string $serverIps)
    {
        return $this->getResponse('GetServerSteamIDsByIP', 'GET', [
            'server_ips' => $serverIps,
        ]);
    }

    /**
     * @param  int  $serverSteamIds
     * @return mixed
     */
    public function getServerIPsBySteamID(int $serverSteamIds)
    {
        return $this->getResponse('GetServerIPsBySteamID', 'GET', [
            'server_steamids' => $serverSteamIds,
        ]);
    }
}

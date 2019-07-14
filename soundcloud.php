<?php
/*
 * Very simple Soundcloud class to fetch track data
 * (c) Dominic Lipscombe
 *
*/

class SoundCloud
{
    const apiEndpoint  = "https://api.soundcloud.com";
    const version      = 0.2;

    private $clientKeys = [
        'client_id' => null,
        'client_secret' => null
    ];

    private $apiUrl;
    private $tempData;

    public function __construct(array $keys)
    {
        $this->setKeys($keys);
    }

    public function setKeys(array $keys): void
    {

        $validKeys = [
            'client_id', 'client_secret'
        ];

        foreach($keys as $key=>$val) {
            if(!in_array($key, $validKeys)) {
                throw new InvalidArgumentException('Only accepts client_id|client_secret.');
            }
            $this->clientKeys[$key] = $val;
        }
    }

    private function buildUrl(string $apiCall, ?array $arguments=null): void
    {
        $argString = null;

        if($arguments !== null) {
            foreach($arguments as $key=>$val) {
                $argString .= '&'.$key.'='.urlencode($val);
            }
        }

        $this->apiUrl = static::apiEndpoint.'/'.$apiCall.'?client_id='.$this->clientKeys['client_id']. $argString;
    }

    private function doRemoteFetch(): bool
    {
        $this->tempData = null;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $output = curl_exec($ch);

        $output = json_decode($output);

        $this->tempData = $output;

        curl_close($ch);

        if (!empty($this->tempData)) return true;
        else return false;
    }

    public function doApiCall(string $apiMethod, ?array $arguments = null)
    {
        $this->buildUrl($apiMethod, $arguments);
        $apiResponse = $this->doRemoteFetch();

        return $apiResponse;
    }

    public function resolveSoundCloudUrl(string $trackUrl): ?int
    {
        $this->doApiCall('resolve',[
            'url' => $trackUrl
        ]);

        if(isset($this->tempData->id) && $this->tempData->id>0) {
            return $this->tempData->id;
        }

        return null;
    }

    public function fetchTrackData(string $trackUrl, ?array $arrayKeysToReturn = null): array
    {
        $finalReturnArray = [];

        $this->doApiCall('resolve', [
            'url' => $trackUrl
        ]);

        if($arrayKeysToReturn == null) {
            return (array) $this->tempData;
        }

        foreach($arrayKeysToReturn as $keyToReturn) {
            $finalReturnArray[$keyToReturn] = (!empty($this->tempData->$keyToReturn)) ? $this->tempData->$keyToReturn : null;
        }

        return $finalReturnArray;
    }

}
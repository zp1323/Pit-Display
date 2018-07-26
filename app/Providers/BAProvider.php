<?php
/*
 * cURL Service Provider for the blue alliance
 */
namespace Providers;

class BAServiceProvider
{
    private $curl;
    private $url;
    private $key;


/** 
 * Sorts the array of matches passed in
 * @param  Array &$array an array of matches
 * @return Void
 */

    public function __construct()
    {
        $this->curl = curl_init();
        $this->url = EnvServiceProvider::get("BLUEALLIANCE_API");
        $this->key = EnvServiceProvider::get("BLUEALLIANCE_KEY");
    }

    public function sortMatches(&$array, $useKey = false) {
        usort($array, function ($a, $b) use ($order, $useKey) {
            if($useKey)
            {
                $a = explode('_', $a['key']);
                $b = explode('_', $b['key']);
            }
            else
            {
                $a = explode('_', $a);
                $b = explode('_', $b);
            }

            // see if the event code is the same
            if ($a[0] != $b[0]) {
                return $a[0] > $b[0];
            }

            // check match type order (Alter if needed)
            $matchTypes = [
                "qm" => 0,
                "qf" => 1,
                "sf" => 2,
                "f" => 3,
            ];

            // match the right hand side of the "_"
            preg_match_all("/([a-z]+)(\d+.*)/", $a[1], $a);
            preg_match_all("/([a-z]+)(\d+.*)/", $b[1], $b);
            // find numerical order equivelent of match type
            $a[1] = $matchTypes[$a[1][0]];
            $b[1] = $matchTypes[$b[1][0]];

            // see if match type if the same
            if($a[1] != $b[1]) {
                return $a[1] > $b[1];
            }

            // same match type return match number comparison
            return $a[2][0] > $b[2][0];
        });
    }

    /**
     * Get e request from blue alliance
     * @param  String request type from blue alliance api
     * @return array
     */
    public function request($request_type)
    {
        curl_setopt($this->curl, CURLOPT_URL, ($this->url . $request_type));
        curl_setopt($this->curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_VERBOSE, 0);
        curl_setopt($this->curl, CURLOPT_HEADER, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'X-TBA-Auth-Key: ' . $this->key,
        ));

        $resp = curl_exec($this->curl);
        //curl_close($this->curl);
        //echo curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $header = substr($resp, 0, $header_size);
        $body = substr($resp, $header_size);

        $return['header_size']  =   $header_size;
        $return['header']       =   $header;
        $return['body']         =   $body;
        $return['response']     =   $resp;

        return $return;
    }

public function getTeamStatus($team_key, $event_key)
{
    $request    =   $this->request('team/'.$team_key.'/event/'.$event_key.'/status');
    return json_decode($request['body'], true);
}

public function getTeamSchedule($team_key, $event_key)
{
    $request    =   $this->request('team/'.$team_key.'/event/'.$event_key.'/matches');
    return json_decode($request['body'], true);
}

public function getMatch($match_key)
{
    $request    =   $this->request('match/'.$match_key);
    return json_decode($request['body'], true);
}

public function getRankings($event_key)
{
    $request    =   $this->request('event/'.$event_key.'/rankings');
    return json_decode($request['body'], true);
}

public function getPredictions($event_key)
{
    $request    =   $this->request('event/'.$event_key.'/predictions');
    return json_decode($request['body'], true);
}

public function getEventSchedule($event_key)
{
    $request    =   $this->request('event/'.$event_key.'/matches/simple');
    return json_decode($request['body'], true);
}

public function getEventTimeseries($event_key, $json = true)
{
    $request    =   $this->request('event/'.$event_key.'/matches/timeseries');
    if($json)
    {
        return json_decode($request['body'], true);
    }
    else
    {
        return $request['body'];
    }
    
}

public function getEventLastMatch($event_key)
{
    $matches    =   $this->getEventTimeseries($event_key);
    $this->sortMatches($matches);
    $last_match     =   end($matches);
    return $this->getMatch($last_match);
}

public function getEventInsights($event_key)
{
    $request    =   $this->request('event/'.$event_key.'/insights');
    return json_decode($request['body'], true);
}

}


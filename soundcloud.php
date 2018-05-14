<?php
/*
 * Very simple Soundcloud class to fetch track data
 * (c) Dominic Lipscombe
 *
*/

class soundcloud
{

    private $api_key;
    private $client_id;
    private $link;
    private $tid;
    private $api_url = "https://api.soundcloud.com";
    private $track_data = array();
    private $url_tmp;

    function __construct($key = null, $id = null)
    {

        if (!empty($key)) $this->set('api_key', $key);
        if (!empty($id)) $this->set('client_id', $id);

        return true;
    }

    /* SET AND GET VARIABLES.
     * INSTEAD OF LOTS OF SEPERATE METHODS WE WILL USE A SIMPLE (VARIABLE_NAME,VARIABLE_VALUE) METHOD
    */

    function set($a, $b)
    {
        if (is_array($this->$a)) return false;

        return $this->$a = $b;
    }

    function get($a)
    {
        return (isset($this->$a) ? $this->$a : false);
    }

    // ** LET'S GO ** //
    function build_url($call, ...$args)
    {

        $arglist = "";

        if (empty($call)) return false;
        if (!empty($args)) foreach ($args as $a) $arglist .= "&{$a}";
        else $arglist = "";

        $this->set('url_tmp', $this->get('api_url') . "/{$call}?client_id=" . $this->get('client_id') . $arglist);
    }

    function fetch()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->get('url_tmp'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        $output = json_decode($output);

        $this->track_data = $output;

        curl_close($ch);

        if (!empty($this->track_data)) return true;
        else return false;
    }

    function resolve($turl)
    {

        $turl = urlencode($turl);

        $this->build_url("resolve.json", "url=" . $turl);
        if ($this->fetch() && intval($this
            ->track_data
            ->status) == 302)
        {

            return ($this->set('url_tmp', $this
                ->track_data
                ->location));

        }
        else return false;
    }

    function track_data(...$d)
    {

        if (empty($d)) return $this->track_data;

        if (is_array($d))
        {

            $return = array();

            foreach ($d as $d)
            {
                $d = (string)$d;

                $return[$d] = $this
                    ->track_data->$d;
            }

        }
        else
        {
            $return = $this->track_data[$d];
        }

        return $return;
    }

}

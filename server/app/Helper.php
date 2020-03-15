<?php
namespace App;

class Helper
{
    /**
     * Using the curl function
     *
     * @param  string  $url : The url to get
     * @param  array  $postData : the POST data
     * @return the curl output
     */
    public function curl($url,$postData=[])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);

        if($postData)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postData));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // curl_setopt($ch, CURLOPT_USERPWD, env('BASIC_USER') . ":" . env('BASIC_PWD'));
        /*$headers =
        [
            'Content-Type: application/json',
          'authorization: Bearer '.CHAT_UI_CHANNEL_ACCESS_TOKEN
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); */

        $output = curl_exec($ch);
        curl_close ($ch);
        return $output;
    }
}
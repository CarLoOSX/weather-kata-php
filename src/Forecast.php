<?php

namespace Codium\CleanCode;

use GuzzleHttp\Client;

class Forecast
{

     const DAYSCONSTANT = "+6 days 00:00:00";
    const LOCATIONCONSTANT = "https://www.metaweather.com/api/location/search/?query=";
    const SEARCHCONSTANT = "https://www.metaweather.com/api/location/";
    const DATEFORMATCONSTANT = 'Y-m-d';

   public function __construct() {

    }

    public function predict(string &$city, \DateTime $datetime = null, bool $wind = false)
    {
        // If there are predictions
        if ($datetime >= new \DateTime(self::DAYCONSTANT))
            return "";
        else $datetime = new \DateTime();


            // Create a Guzzle Http Client
            $client = new Client();

            // Find the id of the city on metawheather
            $woeid = json_decode($client->get(self::SEARCHCONSTANT.$city)->getBody()->getContents(),
                true)[0]['woeid'];
            $city = $woeid;

            // Find the predictions for the city
            $results = json_decode($client->get(self::LOCATIONCONSTANT.$woeid)->getBody()->getContents(),
                true)['consolidated_weather'];
            foreach ($results as $result) {

                // When the date is the expected
                if ($result["applicable_date"] == $datetime->format(self::DATEFORMATCONSTANT)) {
                    // If we have to return the wind information
                    if ($wind) {
                        return $result['wind_speed'];
                    } else {
                        return $result['weather_state_name'];
                    }
                }
            }

    }
}
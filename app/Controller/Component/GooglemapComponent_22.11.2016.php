<?php
/**
 * @author Rajaram.R
 * @copyright 2015
 */

App::uses('Component', 'Controller');

class GooglemapComponent extends Component
{
    /**
     * The Google Maps API key holder
     * @var string
     */


    public function getlatitudeandlongitude($address)
    {

        $prepAddr = str_replace(' ', '+', $address);

        $url = 'http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false';
        

        $c = curl_init();
        // echo "<pre>";print_r($c);exit();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        $output = curl_exec($c);
        curl_close($c);
        $output = json_decode($output, true);

        $latitude = $output['results'][0]['geometry']['location']['lat'];
        $longitude = $output['results'][0]['geometry']['location']['lng'];
        $address_comp = $output['results'][0]['address_components'];
        $city = $state = $country = '';



        if (is_array($address_comp)) {
            foreach ($address_comp as $key => $value) {
                switch ($value['types'][0]) {
                    case 'locality':
                        $city = $value['long_name'];
                        break;

                    case 'administrative_area_level_1':
                        $state = $value['long_name'];
                        break;

                    case 'country':
                        $country = $value['long_name'];
                        break;
                }
            }
        }

        return array('lat' => $latitude, 'long' => $longitude, 'city' => $city, 'state' => $state, 'country' => $country);

    }


    /**
     * Get Driving Distance
     * @param Source latitude & longitude, Destination latitude & longitude
     * @return Distance between source and destination
     */
    public function getDrivingDistance($sourceLat, $sourceLong, $destinationLat, $destinationLong)
    {
        $url = "http://maps.googleapis.com/maps/api/directions/json?origin=$sourceLat,$sourceLong&destination=$destinationLat,$destinationLong&sensor=false";

        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        $jsonResponse = curl_exec($c);
        curl_close($c);
        $dataset = json_decode($jsonResponse);
        if (!$dataset || !isset($dataset->routes[0]->legs[0]->distance->value)) {
            return 0;
        }

        $distance = array(
            'distanceValue' => $dataset->routes[0]->legs[0]->distance->value,
            'distanceText'  => $dataset->routes[0]->legs[0]->distance->text,
            'durationValue' => $dataset->routes[0]->legs[0]->duration->value,
            'durationText'  => $dataset->routes[0]->legs[0]->duration->text
        );

        return $distance;
    }

    function getDistance($addressFrom, $addressTo, $unit=''){

        //Change address format
        $formattedAddrFrom = str_replace(' ','+',$addressFrom);
        $formattedAddrTo = str_replace(' ','+',$addressTo);

        //Send request and receive json data
        $geocodeFrom = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false');
        $outputFrom = json_decode($geocodeFrom);
        $geocodeTo = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false');
        $outputTo = json_decode($geocodeTo);

        //Get latitude and longitude from geo data
        $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
        $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
        $latitudeTo = $outputTo->results[0]->geometry->location->lat;
        $longitudeTo = $outputTo->results[0]->geometry->location->lng;

        //Calculate distance from latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        $unit = strtoupper($unit);
        if ($unit == "K") {
            return ($miles * 1.609344).' km';
        } else if ($unit == "N") {
            return ($miles * 0.8684).' nm';
        } else {
            return number_format($miles, 2);
        }
    }

    function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        return $miles;
    }


    public function getAddress($latitude, $longitude) {


        $geolocation = $latitude.','.$longitude;
        $request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false'; 
        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);

        // echo "<pre>"; print_r($json_decode);

        return $json_decode->results[0]->formatted_address;

        /*if(isset($json_decode->results[0])) {
            $response = array();
            foreach($json_decode->results[0]->address_components as $addressComponet) {
                if(in_array('political', $addressComponet->types)) {
                        $response[] = $addressComponet->long_name; 
                }
            }

            if(isset($response[0])){ $first  =  $response[0];  } else { $first  = 'null'; }
            if(isset($response[1])){ $second =  $response[1];  } else { $second = 'null'; } 
            if(isset($response[2])){ $third  =  $response[2];  } else { $third  = 'null'; }
            if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = 'null'; }
            if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = 'null'; }

            if( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null' ) {
                echo "<br/>Address:: ".$first;
                echo "<br/>City:: ".$second;
                echo "<br/>State:: ".$fourth;
                echo "<br/>Country:: ".$fifth;
            }
            else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null'  ) {
                echo "<br/>Address:: ".$first;
                echo "<br/>City:: ".$second;
                echo "<br/>State:: ".$third;
                echo "<br/>Country:: ".$fourth;
            }
            else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null' ) {
                echo "<br/>City:: ".$first;
                echo "<br/>State:: ".$second;
                echo "<br/>Country:: ".$third;
            }
            else if ( $first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
                echo "<br/>State:: ".$first;
                echo "<br/>Country:: ".$second;
            }
            else if ( $first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
                echo "<br/>Country:: ".$first;
            }
        }*/
    }


} //end class
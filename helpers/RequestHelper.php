<?php

namespace helpers;
/**
 * Split the uri path into parts (array),
 * Note: if you need uri path without domain, set the `$excludeDomain` arg as true.
 *
 * E.g:
 * uri = "google.com/users"
 * returned value is ["google.com", "users"]
 *
 * @param boolean $excludeDomain
 * @return string[]
 *
 */
class RequestHelper
{


    public static function getRequestUriAsArray($excludeDomain=false){
        $request_uri=$_SERVER["REQUEST_URI"];
        $parse_url=parse_url($request_uri);
        $exploded_request_path=explode("/",$parse_url["path"]);


        array_shift($exploded_request_path);

       if ($excludeDomain) {
            array_shift($exploded_request_path);
       }

        return $exploded_request_path;
    }


    /**
     * Get data that  sent  as row string in json format
     *
     * to get as associative array represent the request payload data
     *
     * @return array
     *
     * */
    public static function getRequestPayload(){
        $data_as_string_in_json_format=file_get_contents("php://input");

        if (! $data_as_string_in_json_format)
            return [];

        return json_decode($data_as_string_in_json_format,true);


    }

    /**
     * Get variable that  sent from the request url
     *
     * @return $variable
     * */

    public static function extractResourcefromRequestPath(){

        $path_parts=self::getRequestUriAsArray();

        $resource=array_pop($path_parts);
        if (ctype_digit($resource)){
            return $resource;
        }
        return null;
    }

}
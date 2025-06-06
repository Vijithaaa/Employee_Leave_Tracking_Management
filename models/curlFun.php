
<?php

class ApiReq{
    private $url;

    public function __construct()
    {
        $this->url = "http://localhost/new/leaveTracking_oop/api/serverfile.php";
    }

    function curl_call($data, $action)
    {
        $req = [];
        $req['data'] = $data;
        $req['action'] = $action;
        $finalreq['req'] = json_encode($req);

        $ini = curl_init();
        curl_setopt($ini, CURLOPT_URL, $this->url);
        curl_setopt($ini, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ini, CURLOPT_POSTFIELDS, $finalreq);
        curl_setopt($ini, CURLOPT_HTTPHEADER, ["API-KEY: testuser"]);

        $response = curl_exec($ini);
        curl_close($ini);
        
        $result = json_decode($response, 1);
        
        // echo "called";
        // print_r($result);
        return $result;
    }
}


// $apicall = new ApiClient();
// $apicall->crul_call($data,"action");


?>
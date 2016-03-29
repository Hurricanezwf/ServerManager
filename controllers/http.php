<?php
class http {
    private $curl;

    function __construct() {
        if (is_null($this->curl)) {
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_HEADER, 0);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT_MS, 1000);
            curl_setopt($this->curl, CURLOPT_TIMEOUT_MS, 1000);
        }
    }

    function __destruct() {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
            $this->curl= null;
        }
    }

    // Req Json Format:
    // {
    //     "cmd" : "",
    //     "data": {
    //         ...
    //     }
    // }
    //
    // Res Json Format:
    // {
    //     "code" : 0,
    //     "data" : {
    //         ...
    //     }
    // }
    //
    // @return Object
    // [{
    //     "login_server": {
    //         "state" : 1,
    //         "memory": 100,
    //         "cpu"   : 1.1
    //     },
    //     "gate_server" : {
    //          ...
    //     }
    // }]
    function PostReq($target_url, $json_data) {
        do {
            if (!is_null($this->curl)) {
                curl_setopt($this->curl, CURLOPT_URL, $target_url);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $json_data);

                $res = curl_exec($this->curl);
                if ($res === FALSE) {
                    printf("[E] exec result is false!"); 
                    break;
                }

                $reply = json_decode($res);
                if ($reply->reply_code > 0) {
                    printf("[E] Reply Code > 0 \n");
                    break;
                }

                return $reply->data;
            }
        } while(0);

        $err_ret = array(
            'state' => 'err',    
            'memory'=> 'err',
            'cpu'   => 'err',
        );
        return $err_ret;
    }
}


// for unit test
function testHttp() {
    $http = new http();
    $a = array("name"=>"zwf");
    $json = json_encode($a);
    $res = $http->PostReq("http://123.56.133.116:8085/status", $json);
    print_r($res);
    echo "\n";
}
?>

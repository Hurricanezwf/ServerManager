<?php
class http {
    private $curl;

    function __construct() {
        if (is_null($this->curl)) {
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_HEADER, 0);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT_MS, 1000);
            //curl_setopt($this->curl, CURLOPT_TIMEOUT_MS, 5000);
        }
    }

    function __destruct() {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
            $this->curl= null;
        }
    }

    // @return: 
    // 成功返回json字符串, 失败返回FALSE
    // json 
    // {
    //     "reply_code" : 1,
    //     "data" : {
    //         ....
    //     }
    // }
    function PostReq($target_url, $json_data) {
        if (!is_null($this->curl)) {
            curl_setopt($this->curl, CURLOPT_URL, $target_url);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $json_data);

            $res = curl_exec($this->curl);
            if ($res === FALSE) {
                printf("[E] exec result is false! Msg:%s\n", curl_error($this->curl)); 
                return FALSE;
            }

            $reply = json_decode($res);
            return $reply;
        }

        printf("[E] post request failed! curl is null \n");
        return FALSE;
    }

} // end of class
?>

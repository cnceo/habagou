<?php
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

    $httpdns = new HttpdnsGetRequest;
    $client = new ClusterTopClient("4272","0ebbcccf3434d1aebcdfaf35ffa906");
    $client->gatewayUrl = "http://api.daily.taobao.net/router/rest";
    var_dump($client->execute($httpdns,"6100e23657fb0b223fgsr656dfssdf51134be9a3a5d4b3a365753805"));

?>
<?php
    function my_die($msg)
    {
        die(json_encode(array('ok'=>false, 'msg'=>$msg)));
    }
?>
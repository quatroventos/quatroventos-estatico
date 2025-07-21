<?php

//uso de cupons
if(isset($_POST['cupom']) || $_POST['cupom'] != ''){


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.makersacademy.com.br/admin/api/v2/promotions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Lw-Client: 640f958d453e4c28a0017ebe',
            'Authorization: Bearer rVaKVZpDxMrjDHkPV84aqDniZfwPb4ZneL12SImB',
            'Content-Type: application/json'
        ),
    ));

    $promotions = curl_exec($curl);
    $promotions = json_decode($promotions);

//    echo "<pre>";
//        print_r($promotions);
//    echo "</pre><br><hr><br>";

    curl_close($curl);

    $coupons = array();
    $codes = array();

    foreach($promotions->data as $promotion){
        foreach($promotion->coupons as $coupon) {

//testa se existe a quantidade suficiente
            if($coupon->quantity > $coupon->times_used){
//testa se está dentro da validade
                if (strtotime($coupon->expires) >= strtotime(date('Y-m-d'))) {
                    array_push($coupons,  array('code'=>$coupon->code, 'type'=>$promotion->type, 'quantity'=>$coupon->quantity, 'value'=>$promotion->value));
                    array_push($codes,$coupon->code);
                }
            }

        }
    }

//    echo "<pre>";
//    print_r($coupons);
//    echo "</pre><br><hr><br>";

    if(in_array($_POST['cupom'], $codes)){
        foreach($coupons as $coupon){
            if($coupon['code'] == $_POST['cupom']){
                header('Content-Type: application/json');
                echo json_encode($coupon);
            }
        }
    }else{
        echo 'false';
    }
}

?>

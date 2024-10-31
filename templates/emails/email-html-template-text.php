<?php

echo "\n";

echo "=====================================================================";

echo "\n\n";



echo __("Order", NCM_txt_domain)." #".$ncm_order_id;

echo "\n";

echo " (".$ncm_order_date.")";

echo "\n\n";



echo $ncm_first_name." ".$ncm_last_name;

echo "\n";

echo $ncm_email;

echo "\n";

echo $ncm_phone_no;

echo "\n";

echo $ncm_country;

echo "\n";

echo $comment;

echo "\n\n";



echo "=====================================================================";

echo "\n\n";



foreach( $product as $tour ) {

    echo __('Tour Code', NCM_txt_domain)." : ".$tour['product_id'];

    echo "\n";

    echo __('Tour Name', NCM_txt_domain)." : ".$tour['tour_name'];

    echo "\n";

    echo __('Tour Date', NCM_txt_domain)." : ".$tour['travel_date'];

    echo "\n";

    echo __('Tour Pickup', NCM_txt_domain)." : ".$tour['pickup'];

    echo "\n";

    echo __('Tour Dropoff', NCM_txt_domain)." : ".$tour['dropoff'];

    echo "\n";

    echo __('Passenger Details', NCM_txt_domain)." : ".$tour['passenger_details'];

    echo "\n";



    echo $tour['display_subtotal'];

    echo "\n";

    echo $tour['display_levy']; 

    echo "\n";

    echo $tour['display_total']; 

    echo "\n\n";



    echo "---------------------------------------------------------------------";

}



echo "\n\n";



echo __('Subtotal', NCM_txt_domain)." : ".$ncm_subtotal;

echo "\n";

echo __('Levy', NCM_txt_domain)." : ".$ncm_levy;

echo "\n";

echo __('Payment method', NCM_txt_domain)." : ".$ncm_gateway_name;

echo "\n";

echo __('Total', NCM_txt_domain)." : ".$ncm_total;

echo "\n\n";



echo "=====================================================================";

echo "\n\n";

?>
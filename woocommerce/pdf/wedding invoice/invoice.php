<?php global $wpo_wcpdf; ?>
<?php

$curr_order = $wpo_wcpdf->export->order;
$curr_order_meta = $curr_order->meta_data;

/*** Get Custom Field Info ****/

//RESORT PACKAGE NAME
$rsv_info_initial_deposit = get_post_meta( $curr_order->get_id() , 'initial_deposit', true );

//update_post_meta( 76, 'my_key', 'Steve' );

$curr_order_meta_arr = $curr_order->get_meta_data();

$rsv_initial_deposit_meta = get_post_meta( $curr_order->ID, 'initial_deposit', TRUE);


$rsv_info_payment_arr = array();

foreach($curr_order_meta_arr as $meta_obj):
    if (strpos($meta_obj->key,"payment_") !== false):
        // add to array
        $rsv_info_payment_arr[$meta_obj->key] = $meta_obj->value;
    endif;

endforeach;

// TODO GET ALL POST META AT ONCE

//CURRENT BALANCE
$rsv_info_curr_balance_meta = get_post_meta( $curr_order->ID, 'curr_balance', TRUE);

//RESORT RATE PER GUEST PER NIGHT
$rsv_info_resort_rate_per_guest_per_night = get_post_meta( $curr_order->ID, 'resort_rate_per_guest_per_night', TRUE);

//RESORT TOTAL PER GUEST
$rsv_info_resort_total_per_guest = get_post_meta( $curr_order->ID, 'resort_total_per_guest', TRUE);

// AIR SERVICE RATE PER GUEST
$rsv_info_air_service_rate_per_guest = get_post_meta( $curr_order->ID, 'air_service_rate_per_guest', TRUE);

// AIR SERVICE STATUS
$rsv_info_air_service_requested = get_post_meta( $curr_order->ID, 'air_service_requested', TRUE);

// FLIGHT TOTAL PER GUEST
$rsv_info_air_service_total = get_post_meta( $curr_order->ID, 'air_service_total', TRUE);

// CURRENT PAYMENT AMT
$rsv_info_curr_payment_amt = get_post_meta( $curr_order->ID, 'curr_payment_amt', TRUE);

// PAYMENT DUE DATE
$rsv_info_payment_due_date = get_post_meta( $curr_order->ID, 'payment_due_date', TRUE);

// INVOICE DATE
$rsv_info_invoice_date = get_post_meta( $curr_order->ID, 'invoice_date', TRUE);

// INVOICE NUM
$rsv_info_invoice_num = get_post_meta( $curr_order->ID, 'invoice_num', TRUE);

// FLIGHT TOTAL
$rsv_info_flight_total = get_post_meta( $curr_order->ID, 'flight_total', TRUE);



// INVOICE TOTAL
$rsv_info_invoice_total = $curr_order->get_total();

// INVOICE TOTAL PER GUEST
$rsv_info_invoice_total_per_guest = get_post_meta( $curr_order->ID, 'invoice_total_per_guest', TRUE);

// INVOICE REMARKS
$rsv_info_invoice_remarks = get_post_meta( $curr_order->ID, 'invoice_remarks', TRUE);

// REMAINING PAYMENTS
$rsv_info_remaining = get_post_meta( $curr_order->ID, 'remaining_payments', TRUE);


// GET SEPARATE INVOICE NAME
$rsv_info_sep_bkdn_name = get_post_meta( $curr_order->ID, 'sep_bkdn_name', TRUE);

// GET SEPARATE INVOICE VARIABLES
if (!empty($rsv_info_sep_bkdn_name)):
    $rsv_info_sep_bkdn_num_nights = get_post_meta( $curr_order->ID, 'sep_bkdn_num_nights', TRUE);
    $rsv_info_sep_bkdn_resort_rate_per_night = get_post_meta( $curr_order->ID, 'sep_bkdn_resort_rate_per_night', TRUE);
    $rsv_info_sep_bkdn_resort_total = get_post_meta( $curr_order->ID, 'sep_bkdn_resort_total', TRUE);
    $rsv_info_sep_bkdn_type = get_post_meta( $curr_order->ID, 'sep_bkdn_type', TRUE);
    $rsv_info_sep_bkdn_air_service_rate = get_post_meta( $curr_order->ID, 'sep_bkdn_air_service_rate', TRUE);
    $rsv_info_sep_bkdn_air_service_requested = get_post_meta( $curr_order->ID, 'sep_bkdn_air_service_requested', TRUE);
endif;



// Calculate total payments

$payment_arr = explode("|",$rsv_info_initial_deposit);
$rsv_info_initial_deposit_val = floatval($payment_arr[1]);

//$rsv_info_initial_deposit_val = get_payment_value($rsv_info_initial_deposit,"payment");

$total_payments_made = $rsv_info_initial_deposit_val;
foreach($curr_order_meta as $meta_arr_val):
    if(strpos($meta_arr_val->key,"payment_") !== FALSE):
        $payment_arr = explode("|",$meta_arr_val->value);
        $total_payments_made += floatval($payment_arr[1]);


        //$total_payments_made += get_payment_value($meta_arr_val->value,"payment");
        //$total_payments_made += get_payment_value($meta_arr_val->value,"payment");
    endif;
endforeach;
// Subtract total payments from invoice total
$total_remaining_balance = $curr_order->get_total() - $total_payments_made ;



// Get order

// Get order items

$curr_rsv_order_items = $curr_order->get_items();

// Iterating through each WC_Order_Item objects

foreach( $curr_rsv_order_items  as $item_key => $item_values ):

    ## Using WC_Order_Item methods ##

    // Item ID is directly accessible from the $item_key in the foreach loop or
    $item_product_id = $item_values->get_product_id();

    $item_name = $item_values->get_name(); // Name of the product
    $item_type = $item_values->get_type(); // Type of the order item ("line_item")

    //BILLING INFO

    //DEPOSIT DATE
    $resort_deposit_date = $item_values->get_meta('pa_deposit-date');
    //DEPOSIT STATUS
    $resort_deposit_status = $item_values->get_meta('pa_deposit-status');
    if ($item_product_id == 9):
        // HOTEL RESERVATION PRODUCT
        //**** RESORT RESERVATION INFO ****//

        //RESORT PACKAGE NAME
        $resort_rsv_package_name = $item_values->get_meta('pa_resort-package');

        // NUMBER OF NIGHT
        $resort_num_nights = $item_values->get_meta('pa_number-of-nights');

        // RESORT RESERVATION START DATE
        $resort_rsv_start_date = $item_values->get_meta('pa_reservation-start-date');
        // RESORT RESERVATION END DATE
        $resort_rsv_end_date = $item_values->get_meta('pa_reservation-end-date');
        // TOTAL NUMBER OF ADULT OCCUPANTS
        $resort_rsv_adult_room_occupants = $item_values->get_meta('pa_num-adult-room-occupants');
        // NUMBER OF CHILDREN
        $resort_rsv_num_children = $item_values->get_meta('pa_number-of-children');
        // ROOM GUEST NAMES
        $resort_rsv_room_guest_names = get_post_meta( $curr_order->ID, 'room_guest_names', TRUE);
        // RESERVATION INVOICE FOR
        $resort_rsv_invoice_for = $item_values->get_meta('pa_invoice-for');
        // RESORT TOTAL
        $resort_rsv_total = $item_values->get_total();

    elseif ($item_product_id == 71 ):
        // FLIGHT PRODUCT
        //RESORT PACKAGE NAME
        $flight_rsv_arrival_date = $item_values->get_meta('pa_flight-arrival-date');

        //RESORT PACKAGE NAME
        $flight_rsv_departure_date = $item_values->get_meta('pa_flight-departure-date');

        //RESORT PACKAGE NAME
        $flight_rsv_rate_category = $item_values->get_meta('pa_rate-category');

        // FLIGHT TOTAL
        $flight_rsv_total = $item_values->get_total();

    elseif ($item_product_id == 120):
        // AIRPORT TRANSERS PRODUCT

        // FLIGHT TOTAL
        $airport_transfers_rsv_total = $item_values->get_total();
    endif; // END IF ROYALTON HOTEL PACKAGE ITEM


endforeach;

$air_service_requested_html = '';
if($rsv_info_air_service_requested == "flight"):
    $air_service_requested_html = "Flight";
elseif($rsv_info_air_service_requested == "transfers"):
    $air_service_requested_html = "Airport Transfers ";
endif;



do_action( 'wpo_wcpdf_before_document', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<div class="invoice-wrapper">
    <table class="head container">
        <tr>
            <td class="header">
                Richard Knight &amp; Jessica Martin <br> 5922D Mausser Drive, Orlando, FL 32822
                <br> Tel: 347-291-5631

                <?php /*
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) );
		} */
                ?>
            </td>
            <td class="shop-info">
                <!-- <div class="shop-name"><h3><?php $wpo_wcpdf->shop_name(); ?></h3></div>
			<div class="shop-address"><?php $wpo_wcpdf->shop_address(); ?></div> -->
            </td>
        </tr>
    </table>

    <h1 class="document-type-label">
        <?php if( $wpo_wcpdf->get_header_logo_id() ) echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) ); ?>
    </h1>

    <?php do_action( 'wpo_wcpdf_after_document_label', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

    <table id="invoice-top-info-bar" class="invoice-info-bar bg-color-primary">
        <tr>
            <td id="invoice-num-label-cell">
                Invoice #

            </td>
            <td id="invoice-num-cell"> <?php // echo($rsv_info_invoice_num); ?> <?php $wpo_wcpdf->order_number(); ?></td>
            <td>  </td>
            <td id="invoice-date-cell">  <?php // echo($rsv_info_invoice_date); ?> <?php $wpo_wcpdf->invoice_date(); ?></td>
        </tr>

    </table>

    <table id="invoice-bill-to-info-bar" class="invoice-info-bar invoice-info-bar">
        <tr>
            <td id="bill-to-label-cell">
                Bill To:

            </td>
            <td>  </td>
            <td>  </td>
            <td> Invoice Includes Package Rates For: </td>
        </tr>

    </table>

    <table id="invoice-bill-to-info-table" class="invoice-info-table">
        <tr>



            <td class="address billing-address">
                <!-- <h3><?php _e( 'Billing Address:', 'wpo_wcpdf' ); ?></h3> -->
                <?php $wpo_wcpdf->billing_address(); ?>
                <?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_email']) ) { ?>
                    <div class="billing-email"><?php $wpo_wcpdf->billing_email(); ?></div>
                <?php } ?>
                <?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_phone']) ) { ?>
                    <div class="billing-phone"><?php $wpo_wcpdf->billing_phone(); ?></div>
                <?php } ?>
            </td>
            <td class="invoice-includes-info">
                <?php

                // Format guest names (replace \n with <br>

                $resort_rsv_room_guest_names_formatted = str_replace("\n","<br>", $resort_rsv_room_guest_names);


                if ($resort_rsv_invoice_for == 'self'):
                    // DISPLAY GUEST NAME
                    echo($curr_order->get_billing_first_name() ." " .$curr_order->get_billing_last_name());
                elseif ($resort_rsv_invoice_for == 'all'):
                    // DISPLAY GUEST NAME AS WELL AS ALL OTHER GUEST NAMES
                    echo($curr_order->get_billing_first_name() ." " .$curr_order->get_billing_last_name() ."<br>" .$resort_rsv_room_guest_names_formatted);
                endif; ?>
            </td>

        </tr>

    </table>

    <?php $num_payments_due  = get_post_meta( $curr_order->ID, 'num_payments_due', TRUE);

                    if ($num_payments_due == 3 ){

                        $current_payment_due = round($total_remaining_balance / ($num_payments_due -1),2);   // divide by number of payments

                    } else {

                        $current_payment_due = round($total_remaining_balance / $num_payments_due,2);   // divide by number of payments

                    }



                     $current_payment_due_split_amt = $current_payment_due / 2;
    // get remaining payment amounts
                    // Get current balance

                    $remaining_payments = $current_payment_due;

                    $invoice_due_date_arr= array("7/31/2017","8/15/2017","8/30/2017","9/30/2017");

                    $invoice_start_num = count($invoice_due_date_arr) - $num_payments_due;

                    //SET CURRENT PAYMENT DUE BY DATE FOR LATER ON PAGE
                    $current_payment_due_by_date = $invoice_due_date_arr[$invoice_start_num];
    // DONE WITH CURR PAYMENT CALCS
?>
    <table id="invoice-main-tbl" class="invoice-large-info-table-wrapper">

        <tr id="pmt-sch-row">
            <td>
                <table class="invoice-large-info-table">
                    <tr class="bg-color-primary">
                    <th>Payment Schedule</th>
                    <th>Payment Amount</th>
                    </tr>


                    <?php
                    /*** TODO LOOP THROUGH PAYMENTS MADE - ADD TO table  */



                    $initial_payment_arr = explode("|",$rsv_info_initial_deposit); ?>

                        <tr class="bg-color-secondary">
                    <?php  if (empty($initial_payment_arr[0])): ?>
                      <td > <?php echo("Initial Deposit"); ?> </td>
                    <?php else: ?>
                            <td >  <?php echo($initial_payment_arr[0]); ?></td>
                    <?php endif; ?>

                            <td>$<?php echo(number_format (floatval($initial_payment_arr[1]),2)); ?>  </td>
                        </tr>

                        <?php
                    // there are meta


                        foreach( $rsv_info_payment_arr as $payment_num=> $payment_amt ):

                        // Get payment date
                        $rsv_payment_arr = explode("|",$payment_amt);

                        ?>

                        <tr class="bg-color-secondary">
                            <td ><?php echo($rsv_payment_arr[0]); ?> </td>
                            <td>$<?php echo(number_format (floatval($rsv_payment_arr[1]),2)); ?>  </td>
                        </tr>


                    <?php

                        endforeach;

                        $manual_payment_due_ctr = 0;
                        $manual_curr_pymt_due = 0;

                    while ($invoice_start_num < count($invoice_due_date_arr)):
                        // CHECK IF MANUAL PAYMENT WAS ENTERED WAS SET

                        // MANUAL PAYMENT META =
                        $curr_manual_payment_due = get_post_meta( $curr_order->ID, 'manual_pymt_due_' .$manual_payment_due_ctr, TRUE);


                            // SET MANUAL_CURR_PYMT_DUE TO FIRST MANUAL PAYMENT VALUE
                            if (!empty($curr_manual_payment_due) && $manual_payment_due_ctr == 0):
                                // If manual payment found and its the first value
                                $manual_curr_pymt_due =  $curr_manual_payment_due;

                            endif;

                        ?>

                        <tr><td><?php echo($invoice_due_date_arr[$invoice_start_num]); ?></td>
                            <?php
                            if ($invoice_due_date_arr[$invoice_start_num] == "8/15/2017" ) { ?>
                                <td>$<?php

                                    if (!empty($curr_manual_payment_due)):
                                        // If manual payment found
                                        echo(number_format(floatval($curr_manual_payment_due) , 2));
                                    else:

                                        echo(number_format(floatval($remaining_payments) / 2, 2));

                                    endif; ?></td>

                                <?php
                            }elseif ($invoice_due_date_arr[$invoice_start_num] == "8/30/2017" && $num_payments_due == 3) { ?>
                                <td>$<?php

                                if (!empty($curr_manual_payment_due)):
                                    // If manual payment found
                                    echo(number_format(floatval($curr_manual_payment_due), 2));
                                else:

                                echo(number_format(floatval($remaining_payments) /2, 2));

                                endif;

                                ?></td> <?php

                            }else { ?>
                                <td>$<?php

                                if (!empty($curr_manual_payment_due)):
                                    // If manual payment found
                                    echo(number_format(floatval($curr_manual_payment_due), 2));
                                else:
                                    echo(number_format(floatval($current_payment_due), 2));
                                endif;

                                ?> </td>  <?php
                            } ?>

                        </tr>
                        <?php  $invoice_start_num++;
                            $manual_payment_due_ctr++;

                    endwhile;

                    /*
                        BEFORE MANUAL PAYMENT DUE

                        while ($invoice_start_num < count($invoice_due_date_arr)): ?>
                            <tr><td><?php echo($invoice_due_date_arr[$invoice_start_num]); ?></td>
                                <?php
                                if ($invoice_due_date_arr[$invoice_start_num] == "8/15/2017" ) { ?>
                                    <td>$<?php echo(number_format(floatval($remaining_payments) / 2, 2)); ?></td>

                                    <?php
                                }elseif ($invoice_due_date_arr[$invoice_start_num] == "8/30/2017" && $num_payments_due == 3) { ?>
                                    <td>$<?php echo(number_format(floatval($remaining_payments) /2, 2)); ?></td> <?php

                                }else { ?>
                                    <td>$<?php echo(number_format(floatval($current_payment_due), 2)); ?> </td>  <?php
                                } ?>

                            </tr>
                            <?php  $invoice_start_num++;
                        endwhile;           */

                    ?>

                </table>

            </td>
            <td class="pad-to-right" >
                <table id="travel-details-tbl" class="invoice-large-info-table ">
                    <tr class="bg-color-primary">
                    <th>Travel Details</th>
                    <th>Remarks</th>
                    </tr>
                    <tbody>
                    <tr><td>Resort Package:</td>
                        <td><?php echo($resort_rsv_package_name) ?></td>
                    </tr>
                    <tr><td>Travel Dates:</td>
                        <td><?php echo($resort_rsv_start_date) ?> - <?php echo($resort_rsv_end_date) ?></td>
                    </tr>
                    <tr><td>Number of Nights:</td>
                        <td><?php echo($resort_num_nights) ?></td>
                    </tr>
                    <tr><td>Flight Departure Airport:</td>
                        <td>TBD <!-- <?php echo($resort_rsv_package_name) ?> --></td>
                    </tr>
                    <tr><td>Return Airport:</td>
                        <td>TBD <!--<?php echo($resort_rsv_package_name) ?> --></td>
                    </tr>
                    <tr><td>Adult Guests:</td>
                        <td><?php echo($resort_rsv_adult_room_occupants) ?></td>
                    </tr>
                    <tr><td>Child Guests:</td>
                        <td><?php echo($resort_rsv_num_children) ?></td>
                    </tr>
                    <tr><td>Room Guest Names:</td>
                        <td><?php

                            echo($resort_rsv_room_guest_names_formatted) ?></td>
                    </tr>

                    <tr><td>Resort
                            <?php if($rsv_info_air_service_requested != "none"):  echo(" + " .$air_service_requested_html); endif; ?> Total:</td>
                        <td>$ <?php echo(number_format (floatval($rsv_info_invoice_total),2)) ?></td>
                    </tr>
                    </tbody>
                </table>
                <div class="info-table-sub-text">(Package Breakdown Below)</div>
            </td>
        </tr>

        <tr id="bal-due-row">
            <td>
                <table id="invoice-balance-tbl" class="invoice-large-info-table">
                    <tr><td>Current Balance</td>
                        <td>$<?php echo (number_format (floatval( $total_remaining_balance),2)); ?></td>
                    </tr>
                </table>
                <div class="info-table-sub-text">(Price Includes Taxes and Fees)</div>
            </td>
            <td class="pad-to-right">
                <table class="due-date-info-table ">
                    <tbody>
                    <tr><td>Total Due:</td><td>$<?php
                            if ($current_payment_due_by_date == "8/15/2017" ):

                                // ECHO MANUAL_CURR_PYMT_DUE TO CURR PAYMENT DUE
                                if (!empty($manual_curr_pymt_due) ):
                                    echo(number_format (floatval($manual_curr_pymt_due ),2));
                                else:
                                    echo(number_format (floatval($current_payment_due_split_amt ),2));

                                endif;


                            else:

                                // ECHO MANUAL_CURR_PYMT_DUE TO CURR PAYMENT DUE
                                if (!empty($manual_curr_pymt_due) ):
                                    echo(number_format (floatval($manual_curr_pymt_due ),2));
                                else:
                                    echo(number_format (floatval($current_payment_due),2));

                                endif;



                            endif;

                            ?>

                        </td></tr>
                    <tr><td>By:</td><td><?php echo($current_payment_due_by_date); ?></td></tr>
                    </tbody>
                </table>
            </td>
        </tr>

    </table>





    <table class="invoice-notes-table invoice-text-section">
        <tr>
        <th> Notes</th>
        </tr>
        <tbody>

        <tr><td>
                - Please verify that your name on the invoice is spelled exactly the same as on your passport to avoid reservation issues with the resort and airfare tickets. <br><br>
                *Deposits are non-refundable</td></tr>
        </tbody>
    </table>
    
    
    <br>
    <hr>

    <table class="invoice-instructions-table invoice-text-section">
        <tr>
        <th>Instructions</th>
        </tr>
        <tbody>
        <tr><td>You can send your monthly payment via the following methods:<br><br>
                <ul>
                    <li><strong>Check:</strong> Please make check payable to Richard Knight or Jessica Martin (send to address on top of invoice)</li>
                    <li><strong>Chase QuickPay:</strong> Please send payment to: theknightcouple@gmail.com</li>
                    <li><strong>Venmo:</strong> Send payment to: @richjess-knight
                    <li><strong>Cash App (Credit Card):</strong> Send payment to $theknightcouple</li>
                    <li><strong>IMPORTANT:</strong> If you are sending a payment for multiple people please include their names in the
                        remarks of your payment or memo of your check.</li>
                </ul>

            </td></tr>
        </tbody>
    </table>

    <hr>



    <table class="invoice-large-info-table-wrapper">
        <tr>

            <td>
                <table id="total-package-breakdown-tbl" class="invoice-large-info-table">
                    <tr class="bg-color-primary ">
                    <th>Total Package Breakdown</th>
                    <th>Remarks</th>
                    </tr>
                    <tbody>
                    <tr><td>Resort Package:</td>
                        <td><?php echo($resort_rsv_package_name) ?></td>
                    </tr>
                    <tr><td>Travel Dates:</td>
                        <td><?php echo($resort_rsv_start_date) ?> - <?php echo($resort_rsv_end_date) ?></td>
                    </tr>
                    <tr><td>Adult Guests:</td>
                        <td><?php echo($resort_rsv_adult_room_occupants) ?></td>
                    </tr>
                    <tr><td>Child Guests:</td>
                        <td><?php echo($resort_rsv_num_children) ?></td>
                    </tr>
                    <tr><td>Resort Package Total:</td>
                        <td>$ <?php echo(number_format (floatval($resort_rsv_total),2)) ?></td>
                    </tr>
                    <?php if($rsv_info_air_service_requested != "none"): ?>
                    <tr><td><?php echo($air_service_requested_html); ?> Total (For All Included Guests):</td>
                        <td>$
                            <?php if($rsv_info_air_service_requested == "flight"):
                                echo(number_format (floatval($flight_rsv_total),2));

                            elseif($rsv_info_air_service_requested == "transfers"):
                                echo(number_format (floatval($airport_transfers_rsv_total),2));
                            endif; ?>
                                </td>
                                </tr>
                            <?php else: ?>
                                 <tr>
                                     <td>Airfare / Airport Transfers Total:</td>
                                    <td>N/A</td>
                                 </tr>

                    <?php endif; ?>
                    <tr><td>Resort  <?php if($rsv_info_air_service_requested != "none"):  echo(" + " .$air_service_requested_html); endif; ?>
                            Total (For All Included Guests):</td>
                        <td>$ <?php echo(number_format (floatval($rsv_info_invoice_total),2)) ?></td>
                    </tr>

                    </tbody>
                </table>
            </td>
            <td>
                <table id="per-guest-breakdown" class="invoice-large-info-table float-right">
                    <tr class="bg-color-primary">
                    <th>Package Breakdown

                        <?php if (isset($rsv_info_sep_bkdn_type) ) {

                            if ($rsv_info_sep_bkdn_type == "child"):
                                echo("Per Adult");
                            elseif ($rsv_info_sep_bkdn_type == "other"): ?>
                                For <?php echo($curr_order->get_billing_first_name() ." " .$curr_order->get_billing_last_name()); ?>
                            <?php else: ?>
                                Guest
                            <?php endif;
                        } else { ?>

                            Per Guest <?php
                        } ?>
                         </th>
                    <th>Remarks</th>
                    </tr>

                    <tr><td>Resort Rate Per Guest Per Night</td>
                        <td>$<?php echo (number_format (floatval($rsv_info_resort_rate_per_guest_per_night),2)); ?>
                        </td>
                    </tr><tr><td>Number of Nights</td>
                        <td><?php echo ($resort_num_nights); ?></td>
                    </tr>
                    <tr><td>Resort Total Per Guest</td>
                        <td>$<?php echo (number_format (floatval($rsv_info_resort_total_per_guest),2)); ?></td>
                    </tr>
                    <?php if($rsv_info_air_service_requested != "none"): ?>
                    <tr><td><?php echo($air_service_requested_html); ?> Total Per Guest</td>
                        <td>$<?php echo (number_format (floatval($rsv_info_air_service_rate_per_guest),2)); ?></td>
                    </tr>
                    <?php else: ?>
                        <tr>
                            <td>Airfare / Airport Transfers Total Per Guest:</td>
                            <td>N/A</td>
                        </tr>
                    <?php endif; ?>
                    <tr><td>Resort <?php if($rsv_info_air_service_requested != "none"): echo(" + " .$air_service_requested_html); endif ?> Total Per Guest</td>
                        <td>$<?php
                            // Get flight rate

                            // Get resort rate per guest
                            $invoice_total_per_guest = $rsv_info_air_service_rate_per_guest + $rsv_info_resort_total_per_guest;

                            echo (number_format (floatval($invoice_total_per_guest),2)); ?></td>
                    </tr>
                </table>
            </td>
        </tr>


    </table>
<?php
    // If there is a separate breakdown variable found in order then show html table

if (!empty($rsv_info_sep_bkdn_name)):

    ?>
    <table class="invoice-large-info-table-wrapper">
        <tr>

            <td>

            </td>
            <td>
                <table id="per-guest-breakdown" class="invoice-large-info-table float-right">
                    <tr class="bg-color-primary">
                        $sep_bkdn_package_bkdn_type_text = "Add'l";
                        <?php switch($rsv_info_sep_bkdn_type ):

                                case("child"):
                                    $sep_bkdn_package_bkdn_type_text = "Child";
                                    break;
                                case("adult"):

                                    break;
                                endswitch;

                        ?>

                        <th><?php echo($sep_bkdn_package_bkdn_type_text); ?> Package Breakdown  </th>
                        <th>Remarks</th>
                    </tr>

                    <tr><td>Guest Name</td>
                        <td><?php echo($rsv_info_sep_bkdn_name ); ?>
                        </td>
                    </tr><tr><td>Resort Rate Per Night</td>
                        <td>$<?php echo (number_format (floatval($rsv_info_sep_bkdn_resort_rate_per_night),2)); ?>
                        </td>
                    </tr><tr><td>Number of Nights</td>
                        <td><?php echo ($rsv_info_sep_bkdn_num_nights); ?></td>
                    </tr>
                    <tr><td>Resort Total</td>
                        <td>$<?php echo (number_format (floatval($rsv_info_sep_bkdn_resort_total),2)); ?></td>
                    </tr>
                    <?php if($rsv_info_sep_bkdn_air_service_requested != "none"): ?>
                        <tr><td><?php //TODO

                                $sep_bkdn_air_service_requested_html = '';
                                if($rsv_info_sep_bkdn_air_service_requested == "flight"):
                                    $sep_bkdn_air_service_requested_html = "Flight";
                                elseif($rsv_info_sep_bkdn_air_service_requested == "transfers"):
                                    $sep_bkdn_air_service_requested_html = "Airport Transfers ";
                                endif;
                                echo($sep_bkdn_air_service_requested_html); ?> Total</td>
                            <td>$<?php echo (number_format (floatval($rsv_info_sep_bkdn_air_service_rate),2)); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td>Airfare / Airport Transfers Total</td>
                            <td>N/A</td>
                        </tr>
                    <?php endif; ?>
                    <tr><td>Resort <?php if($rsv_info_sep_bkdn_air_service_requested != "none"): echo(" + " .$sep_bkdn_air_service_requested_html); endif ?> Total</td>
                        <td>$<?php
                            // Get flight rate

                            // Get resort rate per guest
                            $sep_bkdn_invoice_total_per_guest = $rsv_info_sep_bkdn_air_service_rate + $rsv_info_sep_bkdn_resort_total;

                            echo (number_format (floatval($sep_bkdn_invoice_total_per_guest),2)); ?></td>
                    </tr>
                </table>
            </td>
        </tr>


    </table>
    <?php
endif;
?>

</div>
<?php
function get_payment_value($payment_var, $return_type){
    $payment_arr = explode("|",$payment_var);
    switch ($return_type):
        case ("date"):
            return $payment_arr[0];
        case ("payment"):
            return floatval($payment_arr[1]);
        default:
            return $payment_arr;

    endswitch;

} // END FUNCTION


?>


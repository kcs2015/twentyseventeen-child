<?php /* Template Name: Reservation Upload */ 


get_header();

/* START UPLOAD */
//$upload_file_name = 'guest_reservation_import.csv';
//$upload_file_name = 'import_complete-missing.csv';

?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

            <h1>Lets Roll</h1>
       <?php     
            $row = 1;
   // if (($handle = fopen(get_stylesheet_directory_uri() ."/import.csv", "r")) !== FALSE) {
    if (($handle = fopen(get_stylesheet_directory_uri() ."/" .$upload_file_name , "r")) !== FALSE) {
   // if (($handle = fopen(get_stylesheet_directory_uri() ."/import_complete_test.csv", "r")) !== FALSE) {
        while ( ! feof($handle)) {
            ($data = fgetcsv($handle, 1000, ","));
            $num = count($data);
            echo "<p> $num fields in line $row: <br /></p>\n";
            $row++;
            for ($c=0; $c < $num; $c++) {
                echo $c .$data[$c] . "<br />\n";
            }

            // INVOICE FOR (all, self)
            $csv_guest_invoice_for = $data[36];

            // IF INVOICE FOR IS BLANK DO NOT IMPORT
            if ($csv_guest_invoice_for != '' ):

                if ( $csv_guest_invoice_for == 'INVOICE STATUS'):
                    $data_key_headers = array();
                    // DATA KEY HEADERS
                    if ($data[1] == "Passenger Name" ):

                       // loop through data and add all values to $data_key_headers
                            foreach ($data as $csv_header):
                                $data_key_headers[] = $csv_header;
                            endforeach;

                    endif;
                else:

                $csv_guest_val_arr = array(
                    "csv_guest_name" => $data[1], // NAME
                    "csv_guest_address" => $data[18], // ADDRESS
                    "csv_guest_phone" => $data[19], // PHONE
                    "csv_guest_initial_deposit" => $data[17] ."|" . format_input($data[16],array("$",",")), // INITIAL DEPOSIT
                    "csv_guest_resort_package_name" => $data[20] ,// RESORT PACKAGE NAME
                    "csv_guest_start_rsv_date" => $data[4], // TRAVEL DATES
                    "csv_guest_end_rsv_date" => $data[5], // TRAVEL DATES
                    "csv_guest_room_guest_names" => $data[23],  // ROOM GUEST NAMES
                    "csv_guest_resort_rate_per_guest_per_night" => format_input($data[26],array("$",",")),
                    "csv_guest_num_nights" => $data[25], // NUMBER OF NIGHTS
                    "csv_guest_resort_total_per_guest" => format_input($data[27],array("$",",")),  // RESORT TOTAL PER GUEST
                    "csv_guest_resort_total_per_child" => 0,  // RESORT TOTAL PER CHILD ** MAKE VARIABLE TODO ADD COLUMN TO SPREADSHEET
                    "csv_guest_invoice_total_per_guest" => format_input($data[29],array("$",",")), // INVOICE TOTAL PER GUEST
                    "csv_guest_resort_total_for_all_guests" => format_input($data[33],array("$",",")),   // RESORT TOTAL FOR ALL GUESTS
                    "csv_guest_invoice_total" => format_input($data[34],array("$",",")),   // INVOICE TOTAL FOR ALL GUESTS
                    "csv_guest_room_num" => $data[3],   // ROOM NUMBER ** MAKE VARIABLE
                    "csv_guest_notes" => $data[8],   // NOTES ** MAKE VARIABLE
                    "csv_guest_bridal_party" => $data[15],   // BRIDAL PARTY ** MAKE VARIABLE
                    "csv_guest_total_num_adult_room_occupants" => intval($data[22]),  // TOTAL NUMBER OF ROOM OCCUPANTS
                    "csv_guest_total_num_children" => 0, // TOTAL NUMBER OF CHILDREN TODO ADD COLUMN TO SPREADSHEET
                    "csv_guest_invoice_for" =>  $data[36],  // INVOICE FOR (all, self)
                    "csv_air_service_rate_requested_per_guest" => format_input($data[28],array("$",",")),
                    "csv_initial_deposit_date" => $data[17],
                    "csv_ny_flight_status" => $data[38]
                );

                /* Check if email field is valid */
                   if(strpos($data[2],"@") !== FALSE ):

                       $csv_guest_val_arr["csv_guest_email"] = $data[2]; // EMAIL
                   else:
                       $csv_guest_val_arr["csv_guest_email"] = '';
                   endif;

                $csv_guest_val_payment_arr = array();

                // PAYMENT 1
                $data_key_counter = 0;
                $payment_counter = 0;
                foreach($data as $val_key => $csv_val):
                    if (strpos($data_key_headers[$data_key_counter],"PAYMENT ") !== FALSE):

                            // if value is a payment variable add to $csv_guest_val_payment_arr
                            if (!empty($csv_val)):
                               //$csv_split_arr = explode("|",$csv_val);
                                $csv_guest_val_payment_arr[$payment_counter] = $csv_val;

                                $payment_counter++;
                            endif;

                    endif;

                    $data_key_counter++;
                endforeach;

                $csv_guest_val_arr["payments"] = $csv_guest_val_payment_arr;



                 // RESORT + FLIGHT TOTAL


                    $csv_guest_val_arr["air_service_requested"] = "none";

                if ($csv_guest_val_arr["csv_air_service_rate_requested_per_guest"] > 0 && $csv_guest_val_arr["csv_air_service_rate_requested_per_guest"] <300 ):
                      // AIRPORT TRANSFERS TOTAL PER GUEST ** MAKE VARIABLE

                    $csv_guest_val_arr["air_service_requested"] = "transfers";

                elseif ($csv_guest_val_arr["csv_air_service_rate_requested_per_guest"] > 300 ):
                    // FLIGHT TOTAL PER GUEST

                    $csv_guest_val_arr["air_service_requested"] = "flight";

                endif;


               // RESORT TOTAL FOR ALL CHILDREN ** MAKE VARIABLE
                   // $csv_guest_ = $data[''];
               // RESORT TOTAL FOR ALL ADULT GUESTS ** MAKE VARIABLE
                    // $csv_guest_ = $data[''];
               // FLIGHT TOTAL FOR ALL GUESTS

                    if( $csv_guest_val_arr["csv_guest_total_num_children"] == ''):
                        $csv_guest_val_arr["csv_guest_total_num_children"] = 0;
                    endif;

                // TODO REMOVE WHEN COMPLETE
                  /*  $test_guest=  "Sharon Darling";
                   // $test_guest=  "Bernarda Villalona";
                    //$test_guest=  "Serena Castellano";

                    if ( $data[1]== $test_guest):
                            break;
                    endif;
                  */

           // endif; // END CHECK TO CREATE INVOICE
    /*    } // END WHILE
                fclose($handle);
    } // END IF */
        //*************************************
        //  DATA HAS BEEN GATHERED FROM CSV LINE
        //*************************************


       $guest_name_arr = explode(" ", $csv_guest_val_arr["csv_guest_name"]);
       $guest_first_name = '';
       $guest_last_name = '';

       $guest_name_ctr = 0;
       foreach ($guest_name_arr as $guest_name_str) {
            if ($guest_name_ctr ==0):
                $guest_first_name = $guest_name_str;
            elseif ($guest_name_ctr ==1):
                 $guest_last_name = $guest_name_str;
            else:
                $guest_last_name = $guest_last_name .' ' .$guest_name_str;

            endif;
           $guest_name_ctr++;
       }


            // Get User id or create user and get id
            $curr_guest_user =  get_user_by( 'email', $csv_guest_val_arr["csv_guest_email"] );


            if ( $curr_guest_user ) {
                // if user found -> get user id
                $curr_guest_user_id = $curr_guest_user->ID;

        } else {
                // Create new user and get id
                //$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                $random_password =  $guest_first_name .'02468';

                //USERNAME = FIRST NAME LAST INITIAL
                $curr_guest_user_name = strtolower($guest_first_name) .strtolower(substr($guest_last_name,0,1));
                $curr_guest_user_id = wp_create_user( $curr_guest_user_name, $random_password, $csv_guest_val_arr["csv_guest_email"] );
                $user_id = wp_update_user( array( 'ID' => $curr_guest_user_id, 'display_name' => $guest_first_name .' ' .$guest_last_name , 'first_name' => $guest_first_name, 'last_name' => $guest_last_name) );
            }

                // Create a new order
                // $curr_order = $order = new WC_Order();
               // $sample_order = $order = new WC_Order();
                    $sample_order = wc_create_order(array('customer_id' => $curr_guest_user_id));
                // TODO REMOVE ONCE DONE TESTING
                // $curr_order = wc_get_order(179);
                // $curr_order->remove_item(9);


                // PARSE ADDRESS
       $csv_guest_addr_arr = explode("\n", $csv_guest_val_arr["csv_guest_address"] );     // ADDRESS
            $guest_addr_line_1 =   $csv_guest_addr_arr[0];
            $guest_addr_line_2 = '';
             $guest_addr_city_info_index=1;
            if (count( $csv_guest_addr_arr) >2 ):
                 // if this is a line 2 address
                $guest_addr_line_2 =   $csv_guest_addr_arr[1];
                $guest_addr_city_info_index= 2;
            endif;

                // Parse remaining address fields
                $guest_addr_city_state_zip_arr = explode(", " , $csv_guest_addr_arr[$guest_addr_city_info_index]);
               $guest_addr_city = $guest_addr_city_state_zip_arr[0];

                $guest_addr_state_zip_arr  = explode(" " , $guest_addr_city_state_zip_arr[1]);

                $guest_addr_state  = $guest_addr_state_zip_arr[0];
                $guest_addr_zip  = $guest_addr_state_zip_arr[1];


       // *** ADD BILLING INFO TO ORDER
               $address = array(
                   'first_name' => $guest_first_name,
                   'last_name'  => $guest_last_name,
                   'company'    => '',
                   'email'      => $csv_guest_val_arr["csv_guest_email"],
                   'phone'      => $csv_guest_val_arr["csv_guest_phone"],
                   'address_1'  => $guest_addr_line_1,
                   'address_2'  => $guest_addr_line_2,
                   'city'       => $guest_addr_city,
                   'state'      => $guest_addr_state,
                   'postcode'   => $guest_addr_zip,
                   'country'    => 'US'
               );


       // REMOVE ONCE DONE TESTING
            //$curr_order = wc_get_order(179);
            // $curr_order = wc_get_order(188);
             //$curr_order = wc_get_order(233);

            //$curr_order->remove_item(9);

       // GET ORDER ID
          //  $curr_order_id = $curr_order->get_id();

              $sample_order->set_address( $address, 'billing' );

            $resort_order_item = add_hotel_resort_info();
                  //  $curr_order = wc_get_order($curr_order_id);
                 //   $curr_order_line_items = $curr_order->get_items();

            $new_order_item_arr = array($resort_order_item->get_id() => $resort_order_item );

        if($csv_guest_val_arr["air_service_requested"] == 'flight'):
            // ADD FLIGHT PRODUCT
           $airfare_order_item =  add_airfare_product();
            $new_order_item_arr[$airfare_order_item->get_id()] = $airfare_order_item ;



          //  $curr_order = wc_get_order($curr_order_id);
          //  $curr_order_line_items = $curr_order->get_items();



        elseif($csv_guest_val_arr["air_service_requested"] == 'transfers'):
            // ADD TRANSFERS PRODUCT
            $air_transfers_order_item = add_transfers_product();
            $new_order_item_arr[$air_transfers_order_item->get_id()] = $air_transfers_order_item ;


           // $curr_order = wc_get_order($curr_order_id);
           // $curr_order_line_items = $curr_order->get_items();



        else:
            // NO AIR SERVICE REQUESTED


        endif;



        /*
                    $curr_order = wc_create_order(array('customer_id' => $curr_guest_user_id));
                    // Add Items
                        foreach ($new_order_item_arr as $order_item):
                            $curr_order->add_item($order_item);

                            endforeach;
*/

       add_order_custom_meta();

                    $sample_order->save();
                    $curr_order_line_items = $sample_order->get_items();
                    $curr_order_load = wc_get_order($sample_order->get_id());
                    $curr_order_line_items = $curr_order_load->get_items();

                    $sample_order->save_meta_data();
                   // $sample_order->save();

                    //$curr_order_load->save();
                   // $curr_order_load = wc_get_order($curr_order_id);

                    //$curr_order_line_items = $curr_order_load->get_items();
                    $curr_order_line_items = $sample_order->get_items();


                    $sample_order->calculate_totals();
                $test = "who";

       $finish = 0;
                endif; // END ELSE CHECK FOR INVOICE STATUS
            endif; // END CHECK TO CREATE INVOICE
                }
        fclose($handle);
        } // end while file import loop

       function add_hotel_resort_info()
       {// Set Order Item Variables
           global $sample_order, $csv_guest_val_arr;

          // $hotel_product = wc_get_product(9);
           $hotel_product = get_product_by_slug("Royalton Resort Package");

           $hotel_order_item = $sample_order->get_item($sample_order->add_product($hotel_product,1,array('total' => $csv_guest_val_arr["csv_guest_resort_total_for_all_guests"] )));
          // $sample_order->save();
           //$hotel_order_item = $curr_order->get_item($curr_order->add_product($hotel_product));

           //$hotel_order_item = new WC_Order_Item_Product();
                 //$hotel_order_item->set_product($hotel_product);
           // ADD ORDER META
           //wc_add_order_item_meta(9,'pa_deposit-date','2-6-2017');
           $hotel_order_item->add_meta_data('pa_deposit-date', $csv_guest_val_arr["csv_initial_deposit_date"], true);
           $hotel_order_item->add_meta_data('pa_deposit-status', '', true);
           $hotel_order_item->add_meta_data('pa_resort-package',   $csv_guest_val_arr["csv_guest_resort_package_name"], true);
           $hotel_order_item->add_meta_data('pa_number-of-nights', $csv_guest_val_arr["csv_guest_num_nights"], true);
           $hotel_order_item->add_meta_data('pa_reservation-start-date',$csv_guest_val_arr["csv_guest_start_rsv_date"] , true);
           $hotel_order_item->add_meta_data('pa_reservation-end-date', $csv_guest_val_arr["csv_guest_end_rsv_date"], true);
           $hotel_order_item->add_meta_data('pa_num-adult-room-occupants', $csv_guest_val_arr["csv_guest_total_num_adult_room_occupants"], true);
           $hotel_order_item->add_meta_data('pa_number-of-children', $csv_guest_val_arr["csv_guest_total_num_children"], true);
           $hotel_order_item->add_meta_data('pa_invoice-for', $csv_guest_val_arr["csv_guest_invoice_for"], true);

           // Get Adult resort total
            //  $hotel_order_item->set_total($csv_guest_val_arr["csv_guest_resort_total_for_all_guests"]);

                   // $curr_order->add_item($hotel_order_item);
           // $hotel_order_item->add_meta_data('','',true);

           // ADD ORDER ITEM META HERE

                    $hotel_order_item->save();
         //   $curr_order->save();

           //$curr_order_updated = wc_get_order($curr_order->get_id());
           //$curr_order_line_items = $curr_order_updated->get_items();


           //wc_save_order_items($curr_order->get_id(),array($hotel_order_item));
            return $hotel_order_item;

       } // END FUNCTION



       function add_order_custom_meta()
       {// Set Order Item Variables
           global $sample_order, $csv_guest_val_arr;


           $sample_order->add_meta_data('initial_deposit', $csv_guest_val_arr["csv_guest_initial_deposit"], true);

           $payment_counter = 0;
           foreach ($csv_guest_val_arr["payments"] as $payment_val):
               $sample_order->add_meta_data("payment_" . $payment_counter + 1, $csv_guest_val_arr["payments"][$payment_counter], true);

           endforeach;


           $sample_order->add_meta_data('room_guest_names', $csv_guest_val_arr["csv_guest_room_guest_names"], true);

           // FORMAT resort_rate_per_guest_per_night (Remove $)
           $csv_guest_resort_rate_per_guest_per_night_formatted = str_replace("$", "", $csv_guest_val_arr["csv_guest_resort_rate_per_guest_per_night"]);
           $sample_order->add_meta_data('resort_rate_per_guest_per_night', $csv_guest_resort_rate_per_guest_per_night_formatted, true);

           // FORMAT resort_rate_per_guest_per_night (Remove $)
           $csv_guest_resort_total_per_guest_formatted = str_replace("$", "", $csv_guest_val_arr["csv_guest_resort_total_per_guest"]);
           $sample_order->add_meta_data('resort_total_per_guest', $csv_guest_resort_total_per_guest_formatted, true);

           // ADD AIR SERVICE REQUESTED TO ORDER
           $sample_order->add_meta_data('air_service_requested', $csv_guest_val_arr["air_service_requested"], true);

           // ADD AIR SERVICE RATE TO ORDER
           $sample_order->add_meta_data('air_service_rate_per_guest' , $csv_guest_val_arr["csv_air_service_rate_requested_per_guest"], true);

           // ADD NY FLIGHT STATUS TO  ORDER
           if ($csv_guest_val_arr["csv_ny_flight_status"] == ""):
               $sample_order->add_meta_data('ny_flight_status' , "N/A", true);
            else:
                $sample_order->add_meta_data('ny_flight_status' , $csv_guest_val_arr["csv_ny_flight_status"], true);
           endif;



           // ADD PAYMENTS
                //Loop through payments array
                    $payment_counter = 0;
                    foreach($csv_guest_val_arr["payments"] as $payment):
                        // if payment present add to curr_order
                        if (!empty($payment)):
                            $sample_order->add_meta_data("payment_" .$payment_counter, $payment, true);
                            $payment_counter++;
                        endif;
                    endforeach;
            // Add number of payments due
           $sample_order->add_meta_data('num_payments_due' , 3, true);
           $sample_order->add_meta_data('invoice_date' , "June 30, 2017", true);

           $sample_order->save_meta_data();
       } // END FUNCTION


function add_airfare_product()
{// Set Order Item Variables
    global $sample_order, $csv_guest_val_arr;
    // FIND OUT IF USER WANTS AIRPORT TRANSFERS OR FLIGHT

    //$airfare_product = wc_get_product(71);
    $airfare_product = get_product_by_slug("Airfare");
        //$sample_order->save();

    // Add correct Quantity and total
    if ($csv_guest_val_arr["csv_guest_invoice_for"] == "all"):
        // set total number room guests as quantity for airfare
        $total_num_room_guests = $csv_guest_val_arr["csv_guest_total_num_children"] + $csv_guest_val_arr["csv_guest_total_num_adult_room_occupants"];

        $airfare_order_item = $sample_order->get_item($sample_order->add_product($airfare_product,$total_num_room_guests,array('total' => $csv_guest_val_arr["csv_air_service_rate_requested_per_guest"] * $total_num_room_guests)));

            //$airfare_order_item->set_total($csv_guest_val_arr["csv_air_service_rate_requested_per_guest"] * $total_num_room_guests);
            //$airfare_order_item->set_quantity($total_num_room_guests);

    elseif($csv_guest_val_arr["csv_guest_invoice_for"] == "self"):
        $total_num_room_guests = 1;

        $airfare_order_item = $sample_order->get_item($sample_order->add_product($airfare_product,$total_num_room_guests , array('total' => $csv_guest_val_arr["csv_air_service_rate_requested_per_guest"])));

            // $airfare_order_item->set_total($csv_guest_val_arr["csv_air_service_rate_requested_per_guest"]);
            // $airfare_order_item->set_quantity($total_num_room_guests);

    endif;

    // ADD ORDER META
    $airfare_order_item->add_meta_data('pa_deposit-date', $csv_guest_val_arr["csv_initial_deposit_date"]);
    $airfare_order_item->add_meta_data('pa_flight-arrival-date', $csv_guest_val_arr["csv_guest_start_rsv_date"], true);
    $airfare_order_item->add_meta_data('pa_flight-departure-date', $csv_guest_val_arr["csv_guest_end_rsv_date"], true);
    $airfare_order_item->add_meta_data('pa_rate-category', 'flight-deposit-rate-1', true);


    //TODO SET PRODUCT TOTAL PRICE


    // $hotel_order_item->add_meta_data('','',true);

    // ADD ORDER ITEM META HERE

     $airfare_order_item->save();

   // $curr_order->save();
    //$sample_order->save();

   // wc_save_order_items($curr_order->get_id(),array($airfare_order_item));

   // $curr_order_updated = wc_get_order($curr_order->get_id());
   // $curr_order_line_items = $curr_order_updated->get_items();

    return $airfare_order_item;

} // END FUNCTION

       function add_transfers_product()
       {
           global $sample_order, $csv_guest_val_arr;

           //$airport_transfers_product = wc_get_product(120);
           $airport_transfers_product = get_product_by_slug("Airport Transfers");

           $airport_transfers_item = $sample_order->get_item($sample_order->add_product($airport_transfers_product));
           // ADD ORDER META
            // wc_add_order_item_meta(9,'pa_deposit-date',$csv_guest_val_arr["csv_initial_deposit_date"]);

           // Add correct Quantity
           if ($csv_guest_val_arr["csv_guest_invoice_for"] == "all"):
               // set total number room guests as quantity for airfare
               $total_num_room_guests = $csv_guest_val_arr["csv_guest_total_num_children"] + $csv_guest_val_arr["csv_guest_total_num_adult_room_occupants"];
               $airport_transfers_item->set_total($csv_guest_val_arr["csv_air_service_rate_requested_per_guest"] * $total_num_room_guests);
               $airport_transfers_item->set_quantity($total_num_room_guests);

           elseif ($csv_guest_val_arr["csv_guest_invoice_for"] == "self"):
               $total_num_room_guests = 1;
               $airport_transfers_item->set_total($csv_guest_val_arr["csv_air_service_rate_requested_per_guest"]);
               $airport_transfers_item->set_quantity($total_num_room_guests);

           endif;

           //TODO SET PRODUCT TOTAL PRICE


           // $hotel_order_item->add_meta_data('','',true);

           // ADD ORDER ITEM META HERE

           $airport_transfers_item->save();

           $curr_order_updated = wc_get_order($sample_order->get_id());
           $curr_order_line_items = $curr_order_updated->get_items();

          // wc_save_order_items($curr_order->get_id(),array($airport_transfers_item));
           return $airport_transfers_item;

       } // END FUNCTION

       function format_input ($input_var, $format_val_arr, $format_task = "remove", $return_type = "float", $format_val2 =""){
            $output_val =$input_var;

           switch ($format_task):
               case "remove":
                   // loop through $format_val_arr
                   foreach ($format_val_arr as $remove_val):
                       $output_val =  str_replace($remove_val,"",$output_val);
                   endforeach;

                   break;
           endswitch;

           switch ($return_type):
               case "int" :
                   $output_val = intval($output_val);
                   break;

                   case "float" :
                   $output_val = floatval($output_val);
                   break;

           endswitch;

           return $output_val;
       } // END FUNCTION

       function get_product_by_slug($slug){
           $posts = get_posts(array(
               'name' => $slug,
               'posts_per_page' => 1,
               'post_type' => 'product',
               'post_status' => 'publish'
           ));

           if(! $posts ) {
               throw new Exception("NoSuchPostBySpecifiedID");
           }



           return wc_get_product($posts[0]->ID);
       }
       ?>



			<?php
		/*	while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/page/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.*/
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();

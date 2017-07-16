<?php /* Template Name: Reservation Upload */ 


get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

            <h1>Lets Roll</h1>
       <?php     
            $row = 1;
    if (($handle = fopen(get_stylesheet_directory_uri() ."/import.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            echo "<p> $num fields in line $row: <br /></p>\n";
            $row++;
            for ($c=0; $c < $num; $c++) {
                echo $data[$c] . "<br />\n";
            }
                // NAME
                    $csv_guest_name = $data[1];

               // ADDRESS
                    $csv_guest_address = $data[18];

               // PHONE
                    $csv_guest_phone = $data[19];

               // INCLUDE PACKAGE RATES FOR ** MAKE PHP VARIABLE INCLUDE_PACKAGE_RATES_GUEST_NAMES
                   $csv_guest_ = $data[''];

                // INITIAL DEPOSIT (DATE | AMOUNT)
            $csv_guest_initial_deposit = $data[17] ."|" .$data[16] ;
               // PAYMENT 1
            $csv_guest_payment_1 = $data['payment_1'];

               // PAYMENT 2
            $csv_guest_payment_2 = $data['payment_2'];
               // PAYMENT 3
            $csv_guest_payment_3 = $data['payment_3'];
               // PAYMENT 4
            $csv_guest_payment_4 = $data['payment_4'];
               // PAYMENT 5
            $csv_guest_payment_5 = $data['payment_5'];
               // PAYMENT 6
            $csv_guest_payment_6 = $data['payment_6'];
               // PAYMENT 7
            $csv_guest_payment_7 = $data['payment_7'];
               // PAYMENT 8
            $csv_guest_payment_8 = $data['payment_8'];
               // PAYMENT 9
            $csv_guest_payment_9 = $data['payment_9'];
               // PAYMENT 10
            $csv_guest_payment_10 = $data['payment_10'];
            // RESORT PACKAGE NAME
                      $csv_guest_resort_package_name = $data[20];

               // TRAVEL DATES
                    $csv_guest_start_rsv_date = $data[4];
                    $csv_guest_end_rsv_date = $data[5];
               // ROOM GUEST NAMES
                    $csv_guest_room_guest_names = $data[23];
                // RESORT + FLIGHT TOTAL
            $csv_guest_ = $data[''];
               // RESORT RATE PER GUEST PER NIGHT
            $csv_guest_resort_rate_per_guest_per_night = $data[26];
               // NUMBER OF NIGHTS
            $csv_guest_num_nights = $data[25];
               // RESORT TOTAL PER GUEST
            $csv_guest_resort_total_per_guest = $data[27];
               // RESORT TOTAL PER CHILD ** MAKE VARIABLE
            $csv_guest_ = $data[''];

                   $air_travel_status = 'none';
                   $csv_guest_airport_transfers_per_guest = 0;
                   $csv_guest_airfare_per_guest = 0;
                if (intval($data[28]) > 0 && intval($data[28]) >300 ):
                      // AIRPORT TRANSFERS TOTAL PER GUEST ** MAKE VARIABLE
                     $csv_guest_airport_transfers_per_guest= $data[28];
                    $air_travel_status = 'transfers';
                elseif (intval($data[28]) > 300 ):
                    // FLIGHT TOTAL PER GUEST
                    $csv_guest_airfare_per_guest = $data[28];
                    $air_travel_status = 'flight';
                endif;
               // INVOICE TOTAL PER GUEST
                    $csv_guest_invoice_total_per_guest = $data[29];
               // RESORT TOTAL FOR ALL CHILDREN ** MAKE VARIABLE
                    $csv_guest_ = $data[''];
               // RESORT TOTAL FOR ALL ADULT GUESTS ** MAKE VARIABLE
                    $csv_guest_ = $data[''];
               // RESORT TOTAL FOR ALL GUESTS
                    $csv_guest_resort_total_for_all_guests = $data[33];
               // FLIGHT TOTAL FOR ALL GUESTS
                    if($air_travel_status == 'flight') :
                        $csv_guest_flight_total_for_all_guests = $data[32];
                    else:
                        $csv_guest_flight_total_for_all_guests = false;
                    endif;
               // AIRPORT TRANSFERS TOTAL FOR ALL GUESTS ** MAKE VARIABLE
            if($air_travel_status == 'transfer') :
                $csv_guest_transfer_total_for_all_guests = $data[32];
            else:
              $csv_guest_transfer_total_for_all_guests = false;
            endif;
                // INVOICE TOTAL FOR ALL GUESTS
            $csv_guest_ = $data[34];
               // ROOM NUMBER ** MAKE VARIABLE
            $csv_guest_room_num = $data['Rm #'];
               // NOTES ** MAKE VARIABLE
                    $csv_guest_ = $data[''];
             // BRIDAL PARTY ** MAKE VARIABLE
                $csv_guest_bridal_party = $data['Bridal party'];

          }
                fclose($handle);
    }
        // Create a new order

        // Set Order Item Variables


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

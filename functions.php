<?php

/* UPDATE WOOCOMMERCE EMAIL STYLES */

// add_filter( 'woocommerce_email_styles', 'tkc_woocommerce_email_styles' );
function tkc_woocommerce_email_styles( $css ) {
    $css .= "#template_header { background-color: #b88e52; }";
    return $css;
}



?>
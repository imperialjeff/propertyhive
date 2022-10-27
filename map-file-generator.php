<?php
    define( 'WP_USE_THEMES', false );
    require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/wp-load.php' ); 

    global $property;
    $property_ids = get_posts(array(
        'fields'            => 'ids',
        'posts_per_page'    => -1,
        'post_type'         => 'property'
    ));

    $property_details = array();
    foreach ($property_ids as $property_id ) {

        $_photos = get_post_meta( $property_id, '_photos', true );
        $_photo_url = wp_get_attachment_image_url( $_photos[0] );

        // $_price_test = get_post_meta( $property_id, '_price', true );
        // echo '$_price_test: ' . $_price_test;

        if( get_post_meta( $property_id, '_department', true ) == 'residential-sales' ) {
            $_price = '£' . number_format(get_post_meta( $property_id, '_price', true ));
        } else {
            $_price = '£' . number_format(get_post_meta( $property_id, '_rent', true )) . ' ' . get_post_meta( $property_id, '_rent_frequency', true );
        }

        $this_property_details = array(
            'id' => 'marker_' . $property_id,
            'lat' => get_post_meta( $property_id, '_latitude', true ),
            'lng' => get_post_meta( $property_id, '_longitude', true ),
            'html' => '<div style="width: 250px;"><table style="width:100%;text-align:left;border-spacing:0;border-collapse: collapse;font-family: Open Sans, sans-serif;" cellspacing="0" cellpadding="0"><tbody><tr><td style="width: 30%;padding: 10px 5px 5px 10px;"><img src="' . $_photo_url . '" style="width:100px;"></td><td style="width: 70%;padding: 5px 0 5px 5px;"><div style=""><div style="font-size:14px;font-weight: bold;line-height:15px;">' . get_the_title( $property_id ) . '</div><span style="font-weight:bold;font-size:16px;color:#AE8C63;line-height:27px;">' . $_price . '</span></div></td></tr><tr style=";"><td colspan="2"><a style="font-weight:500;background-color:#AE8C63;color:#ffffff;width:100%;height:38px;line-height:38px;display: block;text-align: center;text-decoration: none;font-size: 14px;" href="' . get_permalink( $property_id ) . '" target="_blank">View Details</a></td></tr></tbody></table></div>',
        );
        $property_details[] = $this_property_details;
    }

    $output = '';
    $output .= '
        // last generated on ' . date('m/d/Y h:i:s a', time()) . '

        document.addEventListener("DOMContentLoaded", function() {
            (function($) {
                loadLocratingPlugin({
                    id: \'properties_map\',
                    lat: 51.597943,
                    lng: -1.437638,
                    zoom: 8,
                    icon: \'.\',
                    type: \'transport\',
                    hidestationswhenzoomedout: true,
                    onLoaded: function() {
                        ';
                foreach( $property_details as $property_detail ) {
                    $output .= 'addLocratingMapMarker(\'properties_map\',{
                            ';

                    $output .= 'id: \'' . $property_detail['id'] . '\', 
                            ';

                    $output .= 'lat: \'' . $property_detail['lat'] . '\', 
                            ';

                    $output .= 'lng: \'' . $property_detail['lng'] . '\', 
                            ';

                    $output .= 'html: \'' . $property_detail['html'] . '\', 
                            ';

                    $output .= 'icon: \'https://www.locrating.com/html5/assets/images/marker-icon.png\',
                            ';

                    $output .= 'clickedIcon: \'https://www.locrating.com/html5/assets/images/marker-icon-orange.png\',
                            ';

                    $output .= 'iconHeight: 50,
                            ';

                    $output .= 'iconHeight: 35,
                            ';

                    $output .= '});
                        ';
                }
            $output .= '}
            });
            })(jQuery);
        });
    ';

    file_put_contents('map-generator-script.js', $output);
    // echo $output;
?>

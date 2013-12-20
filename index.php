<?php
/*
Plugin Name: FacetWP - Select2
Plugin URI: https://facetwp.com/
Description: Adds the Select2 facet type
Version: 1.0
Author: Matt Gibbs
Author URI: https://facetwp.com/

Copyright 2013 Matt Gibbs

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

// exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * WordPress init hook
 */
add_action( 'init' , 'fwps2_init' );


/**
 * Intialize facet registration and any assets
 */
function fwps2_init() {
    add_filter( 'facetwp_facet_types', 'fwps2_facet_types' );

    wp_enqueue_script('select2',
        plugins_url( 'facetwp-select2' ) . '/select2/select2.min.js', array( 'jquery' ), '3.4.5' );

    wp_enqueue_style( 'select2',
        plugins_url( 'facetwp-select2' ) . '/select2/select2.css', array(), '3.4.5' );
}


/**
 * Register the facet type
 */
function fwps2_facet_types( $facet_types ) {
    include( dirname( __FILE__ ) . '/facet-select2.php' );
    $facet_types['select2'] = new FacetWP_Facet_Select2();
    return $facet_types;
}
<?php 
/**
 * Misc Helper Functions
 */

/**
 * Takes an angle in degrees and returns text descriptions of that angle.
 *
 * @param float   $angle The angle to be described in degrees (may be positive or negative).
 * @param int   $division Optional. Tumber of cardinal points to be used. Default = 8.
 * @return array Assoc. array containing short and full cardinal directions for the angle.
 */
function cardinal_direction($angle, $division = 8) {

    if ($division == ! in_array($division, array( '2', '4', '8', '16', '32' ))) {
        return false;
    }

    $directions = array(
        '0' => array( 'short' => 'N', 'full' => 'North' ),
        11.25 => array( 'short' => 'NxE', 'full' => 'North by East' ),
        22.5 => array( 'short' => 'NNE', 'full' => 'North North East' ),
        33.75 => array( 'short' => 'NExN', 'full' => 'North East by North' ),
        45 => array( 'short' => 'NE', 'full' => 'North East' ),
        56.25 => array( 'short' => 'NExE', 'full' => 'North East by East' ),
        67.5 => array( 'short' => 'ENE', 'full' => 'East North East' ),
        78.75 => array( 'short' => 'ExN', 'full' => 'East by North' ),
        90 => array( 'short' => 'E', 'full' => 'East' ),
        101.25 => array( 'short' => 'ExS', 'full' => 'East by South' ),
        112.5 => array( 'short' => 'ESE', 'full' => 'East South East' ),
        123.75 => array( 'short' => 'SExE', 'full' => 'South East by East' ),
        135 => array( 'short' => 'SE', 'full' => 'South East' ),
        146.25 => array( 'short' => 'SExS', 'full' => 'South East by South' ),
        157.5 => array( 'short' => 'SSE', 'full' => 'South South East' ),
        168.75 => array( 'short' => 'SxE', 'full' => 'South by East' ),
        180 => array( 'short' => 'S', 'full' => 'South' ),
        191.25 => array( 'short' => 'SxW', 'full' => 'South by West' ),
        202.5 => array( 'short' => 'SSW', 'full' => 'South South West' ),
        213.75 => array( 'short' => 'SWxS', 'full' => 'South West by South' ),
        225 => array( 'short' => 'SW', 'full' => 'South West' ),
        236.25 => array( 'short' => 'SWxW', 'full' => 'South West by West' ),
        247.5 => array( 'short' => 'WSW', 'full' => 'West South West' ),
        258.75 => array( 'short' => 'WxS', 'full' => 'West by South' ),
        270 => array( 'short' => 'W', 'full' => 'West' ),
        281.25 => array( 'short' => 'WxN', 'full' => 'West by North' ),
        292.5 => array( 'short' => 'WNW', 'full' => 'West North West' ),
        303.75 => array( 'short' => 'NWxW', 'full' => 'North West by West' ),
        315 => array( 'short' => 'NW', 'full' => 'North West' ),
        326.25 => array( 'short' => 'NWxN', 'full' => 'North West by North' ),
        337.5 => array( 'short' => 'NNW', 'full' => 'North North West' ),
        348.75 => array( 'short' => 'NxW', 'full' => 'North by West' ),
        );

    $angle = $angle % 360;

    if ($angle < 0) {
        $angle = 360 + $angle;
    }

    $segment_size = 360 / $division;

    $resolved_angle = (float) round($angle / $segment_size) * $segment_size;

    if (360 == $resolved_angle) {
        $resolved_angle = 0;
    }

    $dir = $directions[ $resolved_angle ];

    return array( 
        'resolved_angle' => $resolved_angle, 
        'short_name' => $dir['short'], 
        'full_name' => $dir['full'],
        );
}

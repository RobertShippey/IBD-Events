<?php 

//Takes an angle in degrees and returns a text description of that angle
//Parameters:
//$angle is the angle to be described in degrees (may be positive or negative)
//$division is number of cardinal points to be used. 
// It may be 2 (N or S), 4 (N, E, S, W), 8 (N, NE, W, etc), 16 (N, NNE, NE, ENE, etc) or
// 32 (N, NNE by N, NNE, NNE by E, NE by N, etc)
//Returns:
//Array with keys
// 'resolved_angle' is the numerical value of the returned angle
// 'short_name' is the abbreviation of the text description of the returned angle (N, NE, NNExE, etc)
// 'full_name' is the full text version of the decription of the returned angle (N, North East by North)
//Check division is one of the acceptable angles.  
function cardinal_direction($angle, $division = 8) {

        if ($division == !in_array($division, array('2', '4', '8', '16', '32'))) {
          return FALSE;
        }

        $short_name = array(
            '0' => 'N',
            11.25 => 'NxE',
            22.5 => 'NNE',
            33.75 => 'NExN',
            45 => 'NE',
            56.25 => 'NExE',
            67.5 => 'ENE',
            78.75 => 'ExN',
            90 => 'E',
            101.25 => 'ExS',
            112.5 => 'ESE',
            123.75 => 'SExE',
            135 => 'SE',
            146.25 => 'SExS',
            157.5 => 'SSE',
            168.75 => 'SxE',
            180 => 'S',
            191.25 => 'SxW',
            202.5 => 'SSW',
            213.75 => 'SWxS',
            225 => 'SW',
            236.25 => 'SWxW',
            247.5 => 'WSW',
            258.75 => 'WxS',
            270 => 'W',
            281.25 => 'WxN',
            292.5 => 'WNW',
            303.75 => 'NWxW',
            315 => 'NW',
            326.25 => 'NWxN',
            337.5 => 'NNW',
            348.75 => 'NxW'
        );

        $full_name = array(
            '0' => 'North',
            11.25 => 'North by East',
            22.5 => 'North North East',
            33.75 => 'North East by North',
            45 => 'North East',
            56.25 => 'North East by East',
            67.5 => 'East North East',
            78.75 => 'East by North',
            90 => 'East',
            101.25 => 'East by South',
            112.5 => 'East South East',
            123.75 => 'South East by East',
            135 => 'South East',
            146.25 => 'South East by South',
            157.5 => 'South South East',
            168.75 => 'South by East',
            180 => 'South',
            191.25 => 'South by West',
            202.5 => 'South South West',
            213.75 => 'South West by South',
            225 => 'South West',
            236.25 => 'South West by West',
            247.5 => 'West South West',
            258.75 => 'West by South',
            270 => 'West',
            281.25 => 'West by North',
            292.5 => 'West North West',
            303.75 => 'North West by West',
            315 => 'North West',
            326.25 => 'North West by North',
            337.5 => 'North North West',
            348.75 => 'North by West'
        );

//Make sure angle is 0-359 and positive
        $angle = $angle % 360;

        if ($angle < 0) {
          $angle = 360 + $angle; //Addition beacause angle is negative
        }


//Work out how big each segment is in degrees (e.g NSEW is 90deg segments)
        $segment_size = 360 / $division;

//Resolved angle is the closest 'segment' to the passed $angle
        $resolved_angle = (float) round($angle / $segment_size) * $segment_size;
        if ($resolved_angle == 360) {
          $resolved_angle = 0; //0 will resolve to 360 so set it back to 0
        };

        return array('resolved_angle' => $resolved_angle, 'short_name' => $short_name[$resolved_angle], 'full_name' => $full_name[$resolved_angle]);
      }

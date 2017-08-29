<?php

/**
 *
 * FUNCTIONS THAT CAN BE USED BY THEMES/PLUGINS DEVELOPERS
 * FOR ADDITIONAL RETINA SUPPORT
 *
 */

// Return Retina URL from the Image URL
function wr2x_get_retina_from_url( $url ) {
  global $wr2x_core;
  return $wr2x_core->get_retina_from_url( $url );
}

// Return the retina file if my found for this normal file
function wr2x_get_retina( $file ) {
  global $wr2x_core;
  return $wr2x_core->get_retina( $file );
}

?>

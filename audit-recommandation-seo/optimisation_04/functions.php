<?php

/**
 * Ajoute par défaut le titre de l'image dans l'attribut alt
 */

add_filter('wp_get_attachment_image_attributes', 'default_alt_if_empty', 10, 2);
function default_alt_if_empty($attr, $attachment)
{

  if (empty($attr['alt'])) {
    $attr['alt'] = get_the_title();
  }
  return $attr;
}

<?php

/**
 * Ajoute par défaut le titre de l'image dans l'attribut alt
 */

add_filter('rank_math/frontend/canonical', '__return_false');
function ajouter_meta_canonical()
{
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  if ($paged) {
    $current_url = esc_url(add_query_arg(null, null)); // Récupère l'URL actuelle du navigateur
    echo '<link rel="canonical" href="' . home_url() . $current_url . '" />';
  }
}
add_action('wp_head', 'ajouter_meta_canonical');

add_filter('rank_math/frontend/canonical', '__return_false');
function ajouter_meta_canonical_2()
{
  $current_url = esc_url(add_query_arg(null, null)); // Récupère l'URL actuelle du navigateur
  echo '<link rel="canonical" href="' . home_url() . $current_url . '" />';
}
add_action('wp_head', 'ajouter_meta_canonical_2');

<?php

/**
 * Ajoute le numéro de page dans le titre et la meta description
 */

// RankMath
if (!function_exists('t5_add_page_number_rank_math')) {
  function t5_add_page_number_rank_math($title)
  {
    global $page;
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    !empty($page) && 1 < $page && $paged = $page;

    if ($paged > 1) {
      $title .= ' | ' . sprintf(__('Page %s'), $paged);
    }

    return $title;
  }

  add_filter('rank_math/frontend/title', 't5_add_page_number_rank_math', 100, 1);
  add_filter('rank_math/frontend/description', 't5_add_page_number_rank_math', 100, 1);
}

// Yoast SEO
if (!function_exists('t5_add_page_number')) {
  function t5_add_page_number($s)
  {
    global $page;
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    !empty($page) && 1 < $page && $paged = $page;

    $paged > 1 && $s .= ' | ' . sprintf(__('Page: %s'), $paged);

    return $s;
  }

  add_filter('wp_title', 't5_add_page_number', 100, 1);
  add_filter('wpseo_metadesc', 't5_add_page_number', 100, 1);
}

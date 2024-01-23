<?php

/**
 * 3 manières d'ajouter un Rel Next / Prev
 */

// Numero #1
function cor_rel_next_prev_pagination()
{
  global $paged;
  if (get_previous_posts_link()) { ?>
    <link rel="prev" href="<?php echo get_pagenum_link($paged - 1); ?>">
  <?php
  }
  if (get_next_posts_link()) { ?>
    <link rel="next" href="<?php echo get_pagenum_link($paged + 1); ?>">
<?php
  }
}
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
add_action('wp_head', 'cor_rel_next_prev_pagination');

// Numero #2
if ($page > 1) {
  echo '<link rel="prev" href="' . get_permalink() . 'page/' . ($paged - 1) . '/">';
  echo '<link rel="next" href="' . get_permalink() . 'page/' . ($paged + 1) . '/">';
} else {
  echo '<link rel="next" href="' . get_permalink() . 'page/' . ($paged + 1) . '/">';
}

// Numero #3
if ($_GET['npage'] == '' || $_GET['npage'] == '1') {
  echo '<link rel="next" href="' . get_permalink() . '?npage=' . ($_GET['npage'] + 1) . '/">';
} elseif ($_GET['npage'] && $_GET['npage'] > 1) {
  echo '<link rel="prev" href="' . get_permalink() . '?npage=' . ($_GET['npage'] - 1) . '/">';

  // Vérifier si la page suivante est disponible
  if (!is_page_available($_GET['npage'] + 1)) {
    // Ne générer que le lien "prev"
    echo '';
  } else {
    echo '<link rel="next" href="' . get_permalink() . '?npage=' . ($_GET['npage'] + 1) . '/">';
  }
} elseif ($_GET['npage']) {
  echo '<link rel="prev" href="' . get_permalink() . '?npage=' . ($_GET['npage'] - 1) . '/">';
}

<?php

/**
 * # Obfuscation des liens
 *
 * Pour que cela fonctionne il faut (au choix) :
 * - Ajouter le code suivant dans un wp_nav_menu : 'walker' => new Custom_Nav_Menu_Walker()
 * -- Cela ajoutera la classe "obfuscate" à tous les <a> du menu
 *
 * - Ajouter la classe "obfuscate" à n'importe quel <a>
 */

add_action('wp_loaded', 'buffer_start');
function buffer_start()
{
  ob_start('akn_ofbuscate_buffer');
}
add_action('shutdown', 'buffer_end');
function buffer_end()
{
  ob_end_flush();
}
function akn_ofbuscate_buffer($buffer)
{
  $result = preg_replace_callback('#<a[^>]+((?<=\s)href=(\"|\')([^\"\']*)(\'|\")[^>]+(?<=\s)class=(\"|\')[^\'\"]*(?<!\w|-)obfuscate(?!\w|-)[^\'\"]*(\"|\')|(?<=\s)class=(\"|\')[^\'\"]*(?<!\w|-)obfuscate(?!\w|-)[^\'\"]*(\"|\')[^>]+(?<=\s)href=(\"|\')([^\"\']*)(\'|\"))[^>]*>(.*)<\/a>#miUs', function ($matches) {
    preg_match('#<a[^>]+(?<=\s)class=[\"|\\\']([^\\\'\"]+)[\"|\\\']#imUs', $matches[0], $matches_classes);
    $classes = trim(preg_replace('/\s+/', ' ', str_replace('obfuscate', '', $matches_classes[1])));
    return '<span class="akn-obf-link' . ($classes ? ' ' . $classes : '') . '" data-o="' . base64_encode($matches[3] ?: $matches[10]) . '" data-b="' . ((strpos(strtolower($matches[0]), '_blank') !== false) ? '1' : '0') . '">' . $matches[12] . '</span>';
  }, $buffer);
  return $result;
}
add_action('wp_footer', 'akn_ofbuscate_footer_js');
function akn_ofbuscate_footer_js()
{
?>
  <style>
    .akn-obf-link {
      cursor: pointer;
    }

    /* Ajout du style par défaut suite à l'obfuscation (<a> -> <span>) */
  </style>

  <script>
    jQuery(document).ready(function($) {
      // options you can change
      var deobfuscate_on_right_click = true;
      // function to open link on click
      function akn_ofbuscate_clicked($el, force_blank) {
        if (typeof(force_blank) == 'undefined')
          var force_blank = false;
        var link = atob($el.data('o'));
        var _blank = $el.data('b');
        if (_blank || force_blank)
          window.open(link);
        else
          location.href = link;
      }
      // trigger link opening on click
      $(document).on('click', '.akn-obf-link', function() {
        var $el = $(this);
        if (!$el.closest('.akn-deobf-link').length)
          akn_ofbuscate_clicked($el);
      });
      // trigger link openin in new tab on mousewheel click
      $(document).on('mousedown', '.akn-obf-link', function(e) {
        if (e.which == 2) {
          var $el = $(this);
          if (!$el.closest('.akn-deobf-link').length) {
            akn_ofbuscate_clicked($el, true);
            return true;
          }
        }
      });
      // deobfuscate link on right click so the context menu is a legit menu with link options
      $(document).on('contextmenu', '.akn-obf-link', function(e) {
        if (deobfuscate_on_right_click) {
          var $el = $(this);
          if (!$el.closest('.akn-deobf-link').length) {
            e.stopPropagation();
            var link = atob($el.data('o'));
            var _blank = $el.data('b');
            $el.wrap('<a class="akn-deobf-link" href="' + link + '"' + (_blank ? ' target="_BLANK"' : '') + '></a>').parent().trigger('contextmenu');
            setTimeout(function() {
              $el.unwrap();
            }, 10);
          }
        }
      });
    });
  </script>
<?php
}


/******************************************************\
|* Ajoute la classe "obfuscate" à n'importe quel <a>. *|
\******************************************************/

class Custom_Nav_Menu_Walker extends Walker_Nav_Menu
{
  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
  {
    $classes = empty($item->classes) ? array() : (array) $item->classes;
    $classes[] = 'obfuscate'; // Ajoutez la classe "obfuscate" ici
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

    $output .= '<li' . $class_names . '>';

    $attributes  = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

    $item_output  = $args->before;
    $item_output .= '<a' . $attributes . ' class="obfuscate">';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}

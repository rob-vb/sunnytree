<?php

namespace SunnyTree;

class Sunny_Mega_Menu_Walker extends \Walker_Nav_Menu {

    private $current_mega_parent = null; // <- Houdt de parent bij

    private function get_category_image($item) {
        if (empty($item->url)) return false;

        $path = trim(parse_url($item->url, PHP_URL_PATH), '/');
        $slug = basename($path);
        if (!$slug) return false;

        $term = get_term_by('slug', $slug, 'product_cat');
        if (!$term || is_wp_error($term)) return false;

        $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
        if (!$thumbnail_id) return false;

        return wp_get_attachment_image_url($thumbnail_id, 'large');
    }

    public function start_lvl(&$output, $depth = 0, $args = null) {
    if ($depth === 0 && $this->current_mega_parent) {
        $output .= '<ul class="second"><div class="container"><div class="row"><div class="sunny-desk-left col-md-9">';

        // Voeg automatisch "Alle producten" link toe
        $parent_url = esc_url($this->current_mega_parent->url);
        $parent_title = esc_html($this->current_mega_parent->title);

        $output .= '<li class=" menu-item menu-item-type-taxonomy menu-item-object-product_cat">';
        $output .= '<a href="' . $parent_url . '">Alle producten van ' . $parent_title . '</a>';
        $output .= '</li>';
    } else {
        $output .= '<ul>';
    }
}

    public function end_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0 && $this->current_mega_parent) {
            $output .= '</div>'; // sluit sunny-desk-left

            $image_url = $this->get_category_image($this->current_mega_parent);
            if ($image_url) {
                $output .= '<div class="sunny-desk-right col-md-3">';
                $output .= '<a href="' . esc_url($this->current_mega_parent->url) . '" class="sunny-main-menu-img" style="background-image: url(' . esc_url($image_url) . ');">';
                $output .= '<span class="sunny-megamenu-img-title"><span>Bekijk assortiment</span></span>';
                $output .= '</a></div>';
            }

            $output .= '</div></div></ul>'; // sluit row, container, ul
            $this->current_mega_parent = null; // reset na sluiten
        } else {
            $output .= '</ul>';
        }
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {

        $classes = empty($item->classes) ? [] : (array) $item->classes;

        if ($depth === 0 && in_array('has-mega', $classes)) {
            $this->current_mega_parent = $item; // <- sla parent op
        }

        $class_names = implode(' ', $classes);
        $output .= '<li class="' . esc_attr($class_names) . '">';

        $output .= '<a href="' . esc_url($item->url) . '"';
        if ($depth === 0 && in_array('has-mega', $classes)) $output .= ' class="sunny-more-arrow"';
        $output .= '>' . esc_html($item->title);

        if ($depth === 0 && in_array('has-mega', $classes)) {
            $output .= '<svg class="sunny-next-menu-desk-arrow-super feather feather-chevron-down" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>';
        }
        $output .= '</a>';

        if ($depth === 0 && in_array('has-mega', $classes)) {
            $output .= '<svg class="sunny-next-menu feather feather-chevron-right" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

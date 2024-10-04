<?php
  add_action('propertyhive_property_imported_dezrez_json', 'set_property_type', 10, 2);

  function set_property_type($post_id, $property) {
    wp_suspend_cache_invalidation(false);
    wp_defer_term_counting(false);
    wp_defer_comment_counting(false);

  if (isset($property['PropertyType']) && is_array($property['PropertyType']) && !empty($property['PropertyType'])) {
    $PropertyTypes = array();
    foreach ($property['PropertyType'] as $PropertyType) {
      $PropertyTypes[$PropertyType['SystemName']] = $PropertyType['DisplayName'];
    }
  }

  if (!empty($PropertyTypes)) {
    add_post_meta($post_id, '_property_type', serialize($PropertyTypes));
  }
    
  wp_suspend_cache_invalidation(true);
  wp_defer_term_counting(true);
  wp_defer_comment_counting(true);
}

<?php
// includes/slugify.php

/**
 * Limit the number of hyphens in a string
 */
function createSlug($string) {
    // 1. Convert to lowercase
    $slug = strtolower($string);
    
    // 2. Replace non-letter or non-digits with -
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    
    // 3. Remove duplicate -
    $slug = preg_replace('/-+/', '-', $slug);
    
    // 4. Trim - from start and end
    $slug = trim($slug, '-');
    
    return $slug;
}
?>

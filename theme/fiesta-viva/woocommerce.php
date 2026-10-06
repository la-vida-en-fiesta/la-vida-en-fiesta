<?php
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<main id="contenido" class="site-main">
<?php fiesta_catalog_navigation(); ?>
<?php if (fiesta_number_size_selector()) : fiesta_number_size_entry(); elseif (fiesta_balloon_category_size()) : fiesta_32_balloon_picker(); else : woocommerce_content(); endif; ?>
</main>
<?php get_footer(); ?>

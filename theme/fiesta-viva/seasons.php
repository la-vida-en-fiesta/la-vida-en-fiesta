<?php
if (!defined('ABSPATH')) { exit; }
/** Annual campaigns: expand this list when the next campaign is agreed. */
function fiesta_current_season($date = null) {
    $date = $date ?: new DateTimeImmutable('now', new DateTimeZone('America/Montevideo'));
    // Campaigns run before the event, rather than dressing January for August.
    $day = $date->format('m-d');
    $campaigns = array(
        array('slug'=>'noche-de-la-nostalgia', 'name'=>'Noche de la Nostalgia', 'start'=>'08-01', 'end'=>'08-24', 'label'=>'24 de agosto', 'logo'=>'logo-la-vida-en-fiesta.png', 'art'=>'costume.svg'),
        array('slug'=>'halloween', 'name'=>'Halloween', 'start'=>'10-01', 'end'=>'10-31', 'label'=>'31 de octubre', 'logo'=>'logo-halloween.png', 'art'=>'halloween-decoration.png'),
        array('slug'=>'navidad', 'name'=>'Navidad', 'start'=>'11-25', 'end'=>'12-25', 'label'=>'25 de diciembre', 'logo'=>'logo-la-vida-en-fiesta.png', 'art'=>'birthday.svg'),
    );
    foreach ($campaigns as $campaign) {
        if ($day >= $campaign['start'] && $day <= $campaign['end']) { return $campaign; }
    }
    return null;
}
function fiesta_event_url($slug) {
    $term = get_term_by('slug', $slug, 'product_cat');
    $url = $term ? get_term_link($term) : false;
    return $url && !is_wp_error($url) ? $url : wc_get_page_permalink('shop');
}
add_filter('body_class', function ($classes) {
    $season = fiesta_current_season();
    if ($season) { $classes[] = 'has-season'; $classes[] = 'season-' . $season['slug']; }
    return $classes;
});
remove_action('wp_head', 'wp_site_icon', 99);
add_action('wp_head', function () {
    $season = fiesta_current_season();
    $name = $season ? $season['logo'] : 'logo-la-vida-en-fiesta.png';
    $url = get_template_directory_uri() . '/assets/' . $name . '?v=' . filemtime(__DIR__ . '/assets/' . $name);
    echo '<link rel="icon" type="image/png" href="' . esc_url($url) . '">';
}, 99);
add_action('wp_enqueue_scripts', function () {
    if (!fiesta_current_season()) { return; }
    wp_enqueue_style('fiesta-seasons', get_template_directory_uri() . '/assets/seasons.css', array('fiesta-viva'), filemtime(__DIR__ . '/assets/seasons.css'));
    wp_enqueue_script('fiesta-seasons', get_template_directory_uri() . '/assets/seasons.js', array(), filemtime(__DIR__ . '/assets/seasons.js'), true);
});
function fiesta_witch_svg() {
    return '<svg aria-hidden="true" focusable="false" viewBox="0 0 120 70" fill="currentColor"><path d="m36 27 12-22 10 21 13 3H27zM42 29c-4 13 2 17 14 14l-4-6 8-3-5-6zM42 41 27 56h39l-13-13zM55 43l18 3 7-7 4 4-9 8-21-2zM25 56h86v4H25zM28 54 3 45l3 11-5 10 27-5z"/></svg>';
}
add_action('wp_body_open', function () {
    $season = fiesta_current_season();
    if (!$season || $season['slug'] !== 'halloween') { return; }
    echo '<div class="season-atmosphere" aria-hidden="true"><div class="ground-fog"></div><div class="flying-witch">' . fiesta_witch_svg() . '</div></div>';
});
add_action('wp_footer', function () {
    $season = fiesta_current_season();
    if (!$season || $season['slug'] !== 'halloween' || !is_front_page()) { return; }
    ?>
    <dialog id="halloween-intro" class="halloween-intro" aria-labelledby="intro-title" aria-describedby="intro-description">
        <button type="button" class="intro-skip">Saltar intro</button>
        <div class="intro-fog" aria-hidden="true"></div>
        <div class="intro-scene"><img class="intro-pumpkin" src="<?php echo esc_url(get_template_directory_uri() . '/assets/halloween-intro-pumpkin.png'); ?>" alt="" width="1254" height="1254"></div>
        <div class="intro-copy"><h2 id="intro-title">¿Te animás a entrar?</h2><p id="intro-description">Esta calabaza te abre las puertas de la fiesta.</p><div class="intro-actions"><button type="button" class="intro-enter" data-sound="yes">Entrar con sonido</button><button type="button" class="intro-enter intro-silent" data-sound="no">Entrar sin sonido</button></div></div>
    </dialog>
    <?php
}, 5);

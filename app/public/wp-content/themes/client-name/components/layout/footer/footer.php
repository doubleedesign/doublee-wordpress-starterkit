<?php
$menu_items = Doublee_Frontend::get_nav_menu_items_by_location('footer', array('depth' => 1));
$socials = get_field('social_media_links', 'options');
$footer_blocks_query = new WP_Query(array(
    'post_type'   => array('shared_content'),
    'post_status' => array('publish'),
    'meta_query'  => array(
        array(
            'key'     => 'always_include_in_footer',
            'value'   => true,
            'compare' => '=',
        ),
    ),
));

if ($footer_blocks_query->posts) {
    global $post;
    foreach ($footer_blocks_query->posts as $content) {
        $blocks = parse_blocks($content->post_content);
        if ($blocks) {
            Doublee_Block_Utils::output_custom_blocks($blocks, array(
                'args' => [],
                'post_id' => $post->ID ?? null,
                'parent' => 'footer'
            ));
        }
        wp_reset_postdata();
    }

}
?>

<footer class="site-footer has-dark-background-color">
    <?php if($menu_items) { ?>

    <nav class="site-footer__nav row">
        <?php
        if ($menu_items) { ?>
            <ul class="site-footer__nav__list col-12">
                <?php foreach ($menu_items as $item) { ?>
                    <li class="site-footer__nav__list__item <?php echo implode(' site-footer__nav__list__item--', $item->classes); ?>">
                        <a href="<?php echo $item->url; ?>">
                            <span><?php echo $item->title; ?></span>
                            <?php if (in_array('external', $item->classes)) { ?>
                                <i class="fa-sharp fa-solid fa-up-right-from-square"></i>
                            <?php } ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </nav>
    <?php } ?>

    <?php if($socials) { ?>
        <div class="site-footer__socials row">
            <ul class="social-links">
                <?php foreach ($socials as $social) { ?>
                    <li class="social-links__item">
                        <a class="social-links__item__link" aria-label="<?php echo $social['label']; ?>"
                           href="<?php echo $social['url']; ?>"
                           target="_blank" rel="noopener">
                            <i class="<?php echo $social['font_awesome_icon']; ?>"></i>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <div class="site-footer__fineprint row">
        <div class="col-12">
            <p>Content &copy; <?php echo get_bloginfo('name') . ' 2011-' . date('Y') . '.'; ?></p>
            <small>Website by <a href="https://www.doubleedesign.com.au" target="_blank">Double-E
                    Design</a>.</small>
        </div>
    </div>
</footer>

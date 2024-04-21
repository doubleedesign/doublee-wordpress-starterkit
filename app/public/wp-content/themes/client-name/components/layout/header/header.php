<header id="masthead" class="site-header has-dark-background-color">
	<?php
	$name = get_bloginfo('name');
	$logo = wp_get_attachment_image_url(get_option('options_logo'), 'full');
	$menu = Doublee_Frontend::get_nav_menu_items_by_location('primary', array('depth' => 2));
	?>
    <div data-vue-component="site-navigation" xmlns="schema/components.xsd">
        <site-navigation
                logourl='<?php echo $logo; ?>'
                sitename='<?php echo $name; ?>'
                menu='<?php echo json_encode($menu); ?>'
                background="dark"
        >
        </site-navigation>
    </div>
</header>

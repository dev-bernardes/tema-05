<footer class="site-footer">
  <div class="footer__wrap">
    <div class="footer__grid">
      <!-- Coluna 1: Marca -->
      <div class="footer__brand">
        <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>">
          <?php
          if ( function_exists('the_custom_logo') && has_custom_logo() ) {
            the_custom_logo();
          } else {
            echo '<strong>'.esc_html(get_bloginfo('name')).'</strong>';
          }
          ?>
        </a>
        <?php
          $txt = get_theme_mod('theme_05_footer_logo_text', '');
          if ($txt) {
            echo '<p class="footer__text">'.wp_kses_post(nl2br($txt)).'</p>';
          }
        ?>
      </div>

      <!-- Coluna 2: Links Relevantes -->
      <div class="footer__col">
        <h3 class="footer__title">
          <?php echo esc_html( get_theme_mod('t05_footer_links_title', __('Links Relevantes','tema-05')) ); ?>
        </h3>
        <?php
        wp_nav_menu([
          'theme_location' => 'footer_links',
          'container'      => false,
          'menu_class'     => 'footer__menu',
          'fallback_cb'    => '__return_empty_string',
          'depth'          => 1,
        ]);
        ?>
      </div>

      <!-- Coluna 3: Categorias -->
      <div class="footer__col">
        <h3 class="footer__title">
          <?php echo esc_html( get_theme_mod('t05_footer_cats_title', __('Categorias','tema-05')) ); ?>
        </h3>
        <?php
        wp_nav_menu([
          'theme_location' => 'footer_cats',
          'container'      => false,
          'menu_class'     => 'footer__menu',
          'fallback_cb'    => '__return_empty_string',
          'depth'          => 1,
        ]);
        ?>
      </div>
    </div>
  </div>

  <div class="footer__copy">
    <div class="footer__wrap footer__copy--inner">
      <span>&copy; <?php echo esc_html(date('Y')); ?> — <?php bloginfo('name'); ?></span>
    </div>
  </div>

  

  <?php wp_footer(); ?>
</footer>
</body>
</html>

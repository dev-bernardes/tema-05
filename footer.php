<footer class="site-footer"> <!-- Início do rodapé -->
  <div class="container footer-grid"> <!-- Grid principal do rodapé -->
    <div class="footer-brand"> <!-- Coluna da marca -->
      <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>"> <!-- Link para home -->
        <?php
        if ( function_exists('the_custom_logo') && has_custom_logo() ) { // Se há logo enviada
          the_custom_logo(); // Exibe a logo cadastrada
        } else { // Fallback se não houver logo
          echo '<strong>'.esc_html(get_bloginfo('name')).'</strong>'; // Nome do site
        }
        ?>
      </a>
      <?php
        $txt = get_theme_mod('theme_05_footer_logo_text'); // Busca texto do customizer
        if ($txt) { // Se houver texto
          echo '<p class="footer-text">'.wp_kses_post(nl2br($txt)).'</p>'; // Exibe com quebras de linha
        }
      ?>
    </div>
    <div></div> <!-- Placeholder p/ colunas futuras (links) -->
    <div></div> <!-- Placeholder p/ colunas futuras (categorias) -->
  </div>
  <div class="container footer-copy"> <!-- Barra de copyright -->
    &copy; <?php echo esc_html(date('Y')); ?> — <?php bloginfo('name'); ?> <!-- Ano + nome -->
  </div>
  <?php wp_footer(); ?> <!-- Hook obrigatório antes de fechar body -->
</footer>
</body>
</html>

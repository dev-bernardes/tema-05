<?php // Template de formulário de busca customizado; substitui o padrão do WP ?>
<form role="search" method="get" class="header-search" action="<?php echo esc_url( home_url('/') ); ?>"> <!-- Form de busca que envia para a home com query 's' -->
  <label class="sr-only" for="s"><?php esc_html_e('Buscar','tema-05'); ?></label> <!-- Rótulo acessível escondido -->
  <input id="s" type="search" name="s" placeholder="Faça uma busca..." value="<?php echo get_search_query(); ?>"> <!-- Campo de texto da busca com valor atual -->
  <button type="submit" aria-label="<?php esc_attr_e('Buscar','tema-05'); ?>"> <!-- Botão submit com ícone -->
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.35-4.35m1.35-4.65a7 7 0 11-14 0 7 7 0 0114 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> <!-- Ícone de lupa em SVG -->
  </button> <!-- Fim do botão -->
</form> <!-- Fim do formulário -->

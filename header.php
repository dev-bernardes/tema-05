<!doctype html> <!-- Define o documento como HTML5 -->
<html <?php language_attributes(); ?>> <!-- Aplica atributos de idioma configurados no WP -->
<head> <!-- Início do head -->
  <meta charset="<?php bloginfo('charset'); ?>"> <!-- Charset definido nas configurações do WP -->
  <meta name="viewport" content="width=device-width,initial-scale=1"> <!-- Responsividade básica -->
  <?php wp_head(); ?> <!-- Hook obrigatório: injeta CSS/JS de tema e plugins -->
</head> <!-- Fim do head -->
<body <?php body_class(); ?>> <!-- Abre o body com classes contextuais do WP -->

<header class="site-header"> <!-- Wrapper do header fixo/estilizado -->
  <div class="container header-row"> <!-- Linha com logo, busca e hambúrguer -->
    <a class="site-logo" href="<?php echo esc_url( home_url('/') ); ?>" aria-label="<?php bloginfo('name'); ?>"> <!-- Link da logo para a home -->
      <?php
        if ( function_exists('the_custom_logo') && has_custom_logo() ) { // Verifica se há logo personalizada
          the_custom_logo(); // Exibe a logo enviada no Personalizar
        } else { // Caso não exista uma logo enviada
          echo '<strong>'.esc_html(get_bloginfo('name')).'</strong>'; // Exibe o nome do site como fallback
        }
      ?> <!-- Fim do bloco da logo -->
    </a> <!-- Fecha link da logo -->

    <div class="header-spacer"></div> <!-- Empurra a busca/menu para a direita -->

    <?php get_search_form(); ?> <!-- Renderiza o arquivo searchform.php customizado -->

<button class="hamburger" id="hamburger" aria-controls="primary-menu" aria-expanded="false"> <!-- Botão -->
  <span class="bars" aria-hidden="true"></span> <!-- Barras visíveis -->
  <span class="sr-only"><?php esc_html_e('Abrir menu','tema-05'); ?></span> <!-- Texto acessível -->
</button> <!-- Fim botão hambúrguer -->
  </div> <!-- Fim da linha do header -->

  <nav id="primary-menu" class="container primary-nav" aria-label="<?php esc_attr_e('Menu Principal','tema-05'); ?>"> <!-- Navegação principal (dropdown mobile) -->
    <?php
      wp_nav_menu([
        'theme_location' => 'primary', // Usa o local de menu registrado como 'primary'
        'container'      => false,     // Não envolver com div extra
        'menu_class'     => 'menu',    // Classe aplicada à <ul>
        'fallback_cb'    => '__return_empty_string', // Evita menu padrão se não houver itens
      ]); // Renderiza o menu
    ?> <!-- Fim wp_nav_menu -->
  </nav>
</header> 

            
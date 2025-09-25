<?php // functions.php do TEMA-05
if (!defined('ABSPATH')) exit; // Evita acesso direto ao arquivo

define('THEME_05_VERSION', '0.1.0'); // Versão do tema (útil p/ cache busting)

/* ========= SETUP DO TEMA ========= */
function theme_05_setup () { // Configura recursos globais do tema
  add_theme_support('title-tag'); // Deixa o WP controlar a <title>
  add_theme_support('post-thumbnails'); // Habilita imagens destacadas (obrigatório p/ add_image_size)
  add_theme_support('html5', array('search-form','gallery','caption','style','script','comment-form','comment-list')); // Marcações HTML5 modernas

  add_theme_support('custom-logo', array( // Suporte à logo dinâmica no Customizer
    'height'      => 100, // Altura sugerida
    'width'       => 400, // Largura sugerida
    'flex-height' => true, // Altura flexível
    'flex-width'  => true, // Largura flexível
    'unlink-homepage-logo' => true, // Evita <a> duplo na home
  )); // Fim custom-logo

  register_nav_menus(array( // Registra locais de menus no WP-Admin
    'primary' => __('Menu Principal','tema-05'), // Menu do topo
    'side'    => __('Menu Lateral da Home','tema-05'), // Menu da sidebar esquerda
  )); // Fim register_nav_menus

  /* === Tamanhos de imagem da HERO (use no front-page.php) === */
  add_image_size('t05-hero-main', 775, 480, true); // Destaque central: 775x480 com crop exato (true)
  add_image_size('t05-hero-side', 531, 196, true); // Cards da direita: 531x196 com crop exato (true)
} // Fim theme_05_setup
add_action('after_setup_theme', 'theme_05_setup'); // Executa o setup após o tema carregar

/* ========= ASSETS (CSS/JS) ========= */
function theme_05_assets () { // Enfileira estilos e scripts do tema
  wp_enqueue_style('theme-05-fonts','https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',[],null); // Fonte Roboto do Google Fonts
  wp_enqueue_style('theme-05-style', get_stylesheet_uri(), ['theme-05-fonts'], THEME_05_VERSION); // style.css principal (depende da fonte)
  wp_enqueue_script('theme-05-theme', get_template_directory_uri() . '/assets/js/theme.js', [], THEME_05_VERSION, true); // JS do tema no footer
} // Fim theme_05_assets
add_action('wp_enqueue_scripts', 'theme_05_assets'); // Dispara o enfileiramento no momento correto

/* ========= CUSTOMIZER (opções visuais) ========= */
function theme_05_customize($wp_customize){ // Registra campos na tela "Personalizar"
  // Altura da logo (desktop)
  $wp_customize->add_setting('theme_05_logo_height', [ // Setting armazenada como theme_mod
    'default'           => 44, // Padrão em px
    'transport'         => 'refresh', // Recarrega a página ao salvar
    'sanitize_callback' => 'absint', // Garante número inteiro
  ]); // Fim setting
  $wp_customize->add_control('theme_05_logo_height', [ // Controle numérico
    'label'       => __('Altura da logo (px)','tema-05'), // Rótulo
    'description' => __('Usado no header (desktop).','tema-05'), // Ajuda
    'section'     => 'title_tagline', // Seção "Identidade do site"
    'type'        => 'number', // Tipo number
    'input_attrs' => ['min'=>16,'max'=>120,'step'=>1], // Limites
  ]); // Fim controle

  // Altura da logo (mobile)
  $wp_customize->add_setting('theme_05_logo_height_mobile', [ // Setting p/ mobile
    'default'           => 36, // Padrão em px
    'transport'         => 'refresh', // Recarrega ao salvar
    'sanitize_callback' => 'absint', // Inteiro seguro
  ]); // Fim setting
  $wp_customize->add_control('theme_05_logo_height_mobile', [ // Controle numérico
    'label'       => __('Altura da logo (mobile, px)','tema-05'), // Rótulo
    'section'     => 'title_tagline', // Mesma seção
    'type'        => 'number', // Input number
    'input_attrs' => ['min'=>12,'max'=>100,'step'=>1], // Limites
  ]); // Fim controle

  // Texto abaixo da logo (rodapé)
  $wp_customize->add_setting('theme_05_footer_logo_text', [ // Setting do texto
    'default'           => '', // Vazio por padrão
    'transport'         => 'refresh', // Recarrega ao salvar
    'sanitize_callback' => 'wp_kses_post', // Permite HTML seguro básico
  ]); // Fim setting
  $wp_customize->add_control('theme_05_footer_logo_text', [ // Controle textarea
    'label'       => __('Texto abaixo da logo (rodapé)','tema-05'), // Rótulo
    'section'     => 'title_tagline', // Mesma seção
    'type'        => 'textarea', // Campo multilinha
  ]); // Fim controle
} // Fim theme_05_customize
add_action('customize_register','theme_05_customize'); // Registra os controles no Customizer

/* ========= CSS DINÂMICO (logo) ========= */
function theme_05_customizer_css(){ // Injeta CSS no <head> com alturas da logo
  $h  = (int) get_theme_mod('theme_05_logo_height',44); // Lê altura desktop
  $hm = (int) get_theme_mod('theme_05_logo_height_mobile',36); // Lê altura mobile
  echo "<style id='theme-05-customizer-css'>
      .site-logo img{height:{$h}px} /* altura desktop da logo */
      @media(max-width:640px){.site-logo img{height:{$hm}px}} /* altura mobile da logo */
    </style>"; // Imprime CSS inline
} // Fim theme_05_customizer_css
add_action('wp_head','theme_05_customizer_css'); // Injeta o CSS no head

/* ========= (Opcional) Nomes legíveis dos sizes no Admin ========= */
function theme_05_image_sizes_ui($sizes){ // Adiciona rótulos no seletor de tamanhos da Mídia
  $sizes['t05-hero-main'] = __('Hero Destaque (775×480)','tema-05'); // Mostra o size grande
  $sizes['t05-hero-side'] = __('Hero Lateral (531×196)','tema-05');  // Mostra o size lateral
  return $sizes; // Retorna o array alterado
} // Fim theme_05_image_sizes_ui
add_filter('image_size_names_choose','theme_05_image_sizes_ui'); // Aplica no Admin

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

// Tamanho fixo dos cards Recentes: 316x236 (crop)
add_action('after_setup_theme', function () {
  add_image_size('t05-recent-card', 316, 236, true);
});

// ==== Tamanhos de imagem da seção Categoria em Destaque ====
// ==== Tamanhos de imagem da seção Categoria em Destaque ====
add_action('after_setup_theme', function () {
  add_image_size('t05-cat-feature', 531, 315, true);  // destaque (esquerda)
  add_image_size('t05-cat-list',    187, 140, true);  // lista (direita) — mais largo, como no print
});

// ==== Customizer: cliente escolhe a categoria destacada ====
add_action('customize_register', function(WP_Customize_Manager $wp_customize){
  $wp_customize->add_section('t05_home_section', [
    'title'    => __('Home – Categoria em Destaque','tema-05'),
    'priority' => 30,
  ]);
  $wp_customize->add_setting('t05_featured_cat', [
    'default'           => 0,
    'sanitize_callback' => 'absint',
    'transport'         => 'refresh',
  ]);
  $wp_customize->add_control(new WP_Customize_Category_Control(
    $wp_customize,
    't05_featured_cat_control',
    [
      'label'    => __('Escolha a categoria em destaque','tema-05'),
      'section'  => 't05_home_section',
      'settings' => 't05_featured_cat',
    ]
  ));
});

/** Controle de categorias para o Customizer */
if ( class_exists('WP_Customize_Control') && ! class_exists('WP_Customize_Category_Control') ) {
  class WP_Customize_Category_Control extends WP_Customize_Control {
    public $type = 'dropdown-categories';
    public function render_content() {
      $dropdown = wp_dropdown_categories([
        'name'              => '_customize-dropdown-categories-' . $this->id,
        'echo'              => 0,
        'show_option_none'  => __('— Selecionar —','tema-05'),
        'option_none_value' => '0',
        'selected'          => $this->value(),
        'hide_empty'        => 0,
      ]);
      $dropdown = str_replace('<select', '<select ' . $this->get_link(), $dropdown);
      printf('<label><span class="customize-control-title">%s</span>%s</label>',
        esc_html($this->label), $dropdown);
    }
  }
}

// === Home – segunda seção de categoria (opcional) ===
add_action('customize_register', function(WP_Customize_Manager $wp_customize){

  // Se a seção "t05_home_section" já existe, só anexamos novos controls
  if ( ! $wp_customize->get_section('t05_home_section') ) {
    $wp_customize->add_section('t05_home_section', [
      'title'    => __('Home – Categoria em Destaque','tema-05'),
      'priority' => 30,
    ]);
  }

  // Title opcional para a 2ª seção (fallback usa o nome da categoria)
  $wp_customize->add_setting('t05_featured_title_2', [
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('t05_featured_title_2', [
    'label'   => __('Título da 2ª seção (opcional)','tema-05'),
    'section' => 't05_home_section',
    'type'    => 'text',
  ]);

  // Categoria da 2ª seção
  $wp_customize->add_setting('t05_featured_cat_2', [
    'default'           => 0,
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control(new WP_Customize_Category_Control(
    $wp_customize,
    't05_featured_cat_2',
    [
      'label'    => __('Escolha a categoria da 2ª seção','tema-05'),
      'section'  => 't05_home_section',
      'settings' => 't05_featured_cat_2',
    ]
  ));
});

// --- Menus do rodapé
add_action('after_setup_theme', function () {
  register_nav_menus([
    'footer_links' => __('Links do Rodapé', 'tema-05'),
    'footer_cats'  => __('Categorias do Rodapé', 'tema-05'),
  ]);
});

// --- Customizer: textos do rodapé (título das colunas e texto da marca)
add_action('customize_register', function(WP_Customize_Manager $wp_customize){

  if ( ! $wp_customize->get_section('t05_footer_section') ) {
    $wp_customize->add_section('t05_footer_section', [
      'title'    => __('Rodapé', 'tema-05'),
      'priority' => 60,
    ]);
  }

  // Texto abaixo/ao lado da logo
  $wp_customize->add_setting('theme_05_footer_logo_text', [
    'default'           => '',
    'sanitize_callback' => 'wp_kses_post',
  ]);
  $wp_customize->add_control('theme_05_footer_logo_text', [
    'label'   => __('Texto da coluna da marca', 'tema-05'),
    'type'    => 'textarea',
    'section' => 't05_footer_section',
  ]);

  // Títulos das colunas de menu
  $wp_customize->add_setting('t05_footer_links_title', [
    'default'           => __('Links Relevantes', 'tema-05'),
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('t05_footer_links_title', [
    'label'   => __('Título da coluna 2', 'tema-05'),
    'type'    => 'text',
    'section' => 't05_footer_section',
  ]);

  $wp_customize->add_setting('t05_footer_cats_title', [
    'default'           => __('Categorias', 'tema-05'),
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('t05_footer_cats_title', [
    'label'   => __('Título da coluna 3', 'tema-05'),
    'type'    => 'text',
    'section' => 't05_footer_section',
  ]);
});

add_action('customize_register', function(WP_Customize_Manager $wp_customize){
  if ( ! $wp_customize->get_section('t05_footer_section') ) {
    $wp_customize->add_section('t05_footer_section', [
      'title'    => __('Rodapé', 'tema-05'),
      'priority' => 60,
    ]);
  }

  // Texto abaixo da logo
  $wp_customize->add_setting('theme_05_footer_logo_text', [
    'default'           => '',
    'sanitize_callback' => 'wp_kses_post', // permite quebras de linha/básico
  ]);
  $wp_customize->add_control('theme_05_footer_logo_text', [
    'label'   => __('Texto abaixo da logo', 'tema-05'),
    'type'    => 'textarea',
    'section' => 't05_footer_section',
  ]);
});

add_action('after_setup_theme', function () {
  add_image_size('t05-cat-feature', 700, 400, true);   // destaque (700px largura)
  add_image_size('t05-cat-list',    187, 140, true);   // lista lateral (187x140)
});

add_action('after_setup_theme', function(){
  add_theme_support('post-thumbnails');
  add_image_size('t05-single-hero', 680, 284, true);
});

add_action('after_setup_theme', function(){
  add_image_size('t05-see-thumb', 344, 194, true); // recorte exato para o card 376
});
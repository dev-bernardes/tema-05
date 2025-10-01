<?php
/**
 * Template Name: Página Legal (Políticas / Termos)
 * Description: Gera índice lateral a partir dos H2 do conteúdo. Conteúdo em cartão 980px.
 */

get_header();

/** Filtra conteúdo, injeta IDs em H2 e extrai TOC */
function t05_legal_prepare_content($post_id = null){
  $post_id  = $post_id ?: get_the_ID();
  $html     = apply_filters('the_content', get_post_field('post_content', $post_id));

  // captura todos H2
  preg_match_all('#<h2([^>]*)>(.*?)</h2>#i', $html, $m, PREG_SET_ORDER);
  $toc = [];
  $i   = 0;

  // injeta ids sequenciais topico-1, topico-2...
  $html = preg_replace_callback(
    '#<h2([^>]*)>(.*?)</h2>#i',
    function($matches) use (&$i, &$toc){
      $i++;
      $id    = 'topico-' . $i;
      $attrs = $matches[1];
      $text  = wp_strip_all_tags($matches[2]);
      $toc[] = ['id'=>$id, 'text'=>$text, 'num'=>$i];

      // mantém atributos existentes e adiciona id (ou substitui)
      if (preg_match('/\sid=["\'].*?["\']/', $attrs)) {
        $attrs = preg_replace('/\sid=["\'].*?["\']/', ' id="'.$id.'"', $attrs);
      } else {
        $attrs .= ' id="'.$id.'"';
      }
      return '<h2'.$attrs.'>'.$matches[2].'</h2>' . '<hr class="legal__sep" />';
    },
    $html
  );

  // remove divisor à esquerda da primeira seção (se quiser, tira a 1ª linha)
  $html = preg_replace('#^(\s*<hr class="legal__sep"\s*/?>)#i', '', $html);

  return ['html'=>$html, 'toc'=>$toc];
}

$prep = t05_legal_prepare_content();
$title = get_the_title();
?>

<main class="legal-page">
  <div class="legal__wrap">

    <!-- ASIDE: ÍNDICE (TÓPICO 1..N) -->
    <aside class="legal__aside">
      <nav aria-label="Índice da página">
        <ul class="legal__toc">
          <?php if (!empty($prep['toc'])): ?>
            <?php foreach ($prep['toc'] as $row): ?>
              <li><a href="#<?php echo esc_attr($row['id']); ?>">
                <?php echo 'TÓPICO ' . (int)$row['num']; ?>
              </a></li>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- fallback com 6 slots “mudos” quando não há H2 ainda -->
            <?php for($i=1;$i<=6;$i++): ?>
              <li><a href="#"><?php echo 'TÓPICO '.$i; ?></a></li>
            <?php endfor; ?>
          <?php endif; ?>
        </ul>
      </nav>
    </aside>

    <!-- CONTEÚDO (980px) -->
    <section class="legal__content" aria-labelledby="legal-title">
      <h1 id="legal-title" class="legal__title"><?php echo esc_html($title); ?></h1>

      <div class="legal__card">
        <?php
          // Intro opcional (excerpt) antes do corpo
          if (has_excerpt()) {
            echo '<p class="legal__intro">'.wp_kses_post(get_the_excerpt()).'</p>';
            echo '<hr class="legal__sep" />';
          }
          // Corpo já com H2 identificados e divisores após cada H2
          echo $prep['html'];
        ?>
      </div>
    </section>
  </div>

  <?php
  // ====== VEJA TAMBÉM (4 + mais lidos) ======
  // Tenta por meta 't05_views' (ou mude para a meta que você usa). Cai para "recentes" se não houver.
  $args_pop = [
    'posts_per_page'      => 4,
    'post__not_in'        => [ get_the_ID() ],
    'ignore_sticky_posts' => true,
    'meta_key'            => 't05_views',     // troque pela sua meta de visualizações, ex.: 'post_views_count'
    'orderby'             => 'meta_value_num',
    'order'               => 'DESC',
  ];
  $q_see = new WP_Query($args_pop);
  if (!$q_see->have_posts()) {
    $q_see = new WP_Query([
      'posts_per_page'      => 4,
      'post__not_in'        => [ get_the_ID() ],
      'ignore_sticky_posts' => true,
      'orderby'             => 'date',
      'order'               => 'DESC',
    ]);
  }
?>

<section class="t05-seealso" aria-labelledby="sa-title">
  <h2 id="sa-title" class="sa-title">Veja Também</h2>

  <div class="sa-grid">
    <?php if ($q_see->have_posts()): while ($q_see->have_posts()): $q_see->the_post(); ?>
      <article class="sa-card">
        <header class="sa-head">
          <?php $c=get_the_category(); if(!empty($c)): ?>
            <span class="sa-cat"><i aria-hidden="true"></i><?php echo esc_html($c[0]->name); ?></span>
          <?php endif; ?>
        </header>

        <a class="sa-thumb" href="<?php the_permalink(); ?>">
          <?php
            // usa tamanho 1:1 com padding (344×194) se existir, senão fallback
            $thumb_size = 'medium_large';
            if (function_exists('wp_get_registered_image_subsizes')) {
              $reg = wp_get_registered_image_subsizes();
              if (isset($reg['t05-see-thumb'])) $thumb_size = 't05-see-thumb';
            }
            has_post_thumbnail() && the_post_thumbnail($thumb_size, ['alt'=>esc_attr(get_the_title())]);
          ?>
        </a>

        <div class="sa-body">
          <h3 class="sa-title-post"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <a class="sa-btn" href="<?php the_permalink(); ?>">Ler mais</a>
        </div>
      </article>
    <?php endwhile; wp_reset_postdata(); endif; ?>
  </div>
</section>

  <!-- ===== Newsletter ===== -->
<section class="nl section">
  <div class="nl__wrap">
    <div class="nl__grid">
      <div class="nl__left">
        <h2 class="nl__title">Nossa Newsletter</h2>
        <p class="nl__desc">
          Quer ficar por dentro dos nossos conteúdos imperdíveis?
          Inscreva-se na nossa newsletter!
        </p>
      </div>

      <div class="nl__right">
        <form class="nl__form" action="#" method="post" novalidate>
          <label class="screen-reader-text" for="nl-email">Seu e-mail</label>
          <input id="nl-email" class="nl__input" type="email" name="email" placeholder="Digite seu melhor e-mail" required>
          <button class="nl__btn" type="submit">INSCREVER-SE</button>
        </form>
        <p class="nl__consent">
          Ao se inscrever, você concorda com nossa Política de Privacidade e consente em receber atualizações de nossa empresa.
        </p>
      </div>
    </div>
  </div>
</section>
</main>

<?php get_footer(); ?>

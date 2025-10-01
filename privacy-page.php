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
</main>

<?php get_footer(); ?>

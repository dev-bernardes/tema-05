<?php get_header(); ?> <!-- header -->

<main class="container section"> <!-- container da home -->
  <div class="hero-layout"> <!-- grid: sidebar | destaque | 2 cards -->

    <aside class="side-nav"> <!-- menu lateral -->
      <?php if (has_nav_menu('side')): // usa o menu criado no WP ?>
        <?php wp_nav_menu([
          'theme_location'=>'side',   // local registrado
          'container'=>false,         // sem div extra
          'menu_class'=>'side-nav__list' // classe da <ul>
        ]); ?>
      <?php else: // fallback simples se o cliente não configurou ainda ?>
        <ul class="side-nav__list"> <!-- lista padrão -->
          <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Início','tema-05'); ?></a></li> <!-- link home -->
          <?php foreach(get_categories(['number'=>4]) as $c): ?> <!-- 4 categorias -->
            <li><a href="<?php echo esc_url(get_category_link($c)); ?>"><?php echo esc_html($c->name); ?></a></li> <!-- link cat -->
          <?php endforeach; ?> <!-- fim loop cats -->
        </ul> <!-- fim lista -->
      <?php endif; ?> <!-- fim condicional menu -->
    </aside> <!-- fim sidebar -->

    <?php $hero_q = new WP_Query([
      'posts_per_page'=>3,          // 3 posts para a hero
      'ignore_sticky_posts'=>false, // mantém sticky
      'no_found_rows'=>true         // performance
    ]); ?> <!-- cria query -->

    <?php if($hero_q->have_posts()): ?> <!-- checa posts -->
      <section class="hero-main"> <!-- coluna central (post destaque) -->
        <?php $hero_q->the_post(); ?> <!-- 1º post -->
        <article <?php post_class('hero-main__inner'); ?>> <!-- article -->
          <a class="post-card__thumb hero-main__thumb" href="<?php the_permalink(); ?>"> <!-- thumb -->
            <?php the_post_thumbnail('t05-hero-main', ['alt'=>esc_attr(get_the_title())]); ?> <!-- usa 775x480 -->
          </a> <!-- fecha thumb -->
          <div class="hero-main__content"> <!-- texto -->
            <h2 class="post-card__title hero-main__title"><?php the_title(); ?></h2> <!-- título -->
            <p class="post-card__excerpt hero-main__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p> <!-- resumo -->
            <a class="btn btn--primary btn-leia-mais" href="<?php the_permalink(); ?>"> <!-- botão principal -->
              <?php esc_html_e('LEIA MAIS','tema-05'); ?> <span aria-hidden="true">→</span> <!-- texto + seta -->
            </a>
          </div> <!-- fim conteúdo -->
        </article> <!-- fim article -->
      </section> <!-- fim hero-main -->

      <aside class="hero-side"> <!-- coluna direita (2 cards) -->
        <?php while($hero_q->have_posts()): $hero_q->the_post(); ?> <!-- 2 restantes -->
          <article <?php post_class('hero-side__card'); ?>> <!-- card com borda verde -->
            <a class="post-card__thumb hero-side__thumb" href="<?php the_permalink(); ?>"> <!-- thumb -->
              <?php the_post_thumbnail('t05-hero-side',  ['alt'=>esc_attr(get_the_title())]); ?> <!-- usa 531x196 -->
            </a> <!-- fecha thumb -->

            <div class="hero-side__content"> <!-- bloco textual do card lateral -->
              <h3 class="post-card__title hero-side__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h3> <!-- título menor -->

              <p class="post-card__excerpt hero-side__excerpt">
                <?php
                  // resumo curto para o lateral (mantém consistência com o hero principal)
                  echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) );
                ?>
              </p> <!-- resumo lateral -->

              <a class="btn btn--primary btn--sm" href="<?php the_permalink(); ?>"> <!-- botão pequeno -->
                <?php esc_html_e('Ler mais','tema-05'); ?> <span aria-hidden="true">→</span>
              </a>
            </div> <!-- fim conteúdo lateral -->
          </article> <!-- fim card -->
        <?php endwhile; wp_reset_postdata(); ?> <!-- reseta query -->
      </aside> <!-- fim coluna direita -->
    <?php endif; ?> <!-- fim condicional posts -->

  </div> <!-- fim grid hero -->

  <?php
  /* ===========================
  *  Seção: Postagens Recentes
  *  Quatro posts mais novos
  * =========================== */
  ?>

  <section class="home-recent section"> <!-- bloco recente -->
  <h2 class="section__title">Postagens Recentes</h2> <!-- título da seção -->

  <?php
    $recent_q = new WP_Query([
      'posts_per_page'      => 4,        // 4 cards
      'ignore_sticky_posts' => true,     // ignora sticky
      'no_found_rows'       => true      // performance
    ]);
  ?>

  <?php if ( $recent_q->have_posts() ): ?>
    <div class="cards-grid cards-grid--recent"> <!-- grid 4 col -->
      <?php while ( $recent_q->have_posts() ): $recent_q->the_post(); ?>
        <article <?php post_class('card card--recent'); ?>> <!-- card com borda roxa -->

          <?php $cat = get_the_category(); if ( ! empty( $cat ) ): ?>
            <span class="post-tag post-tag--recent"> <!-- chip de categoria -->
              <?php echo esc_html( $cat[0]->name ); ?>
            </span>
          <?php endif; ?>

          <a class="card__thumb" href="<?php the_permalink(); ?>"> <!-- imagem -->
            <?php
              // Use um size registrado (ex: t05-card) ou fallback para 'medium_large'
              if ( has_post_thumbnail() ) {
                the_post_thumbnail('t05-recent-card', ['alt' => esc_attr(get_the_title())]);
              } else {
                echo '<img src="'.esc_url(get_template_directory_uri().'/assets/img/placeholder-4x3.jpg').'" alt="'.esc_attr(get_the_title()).'">';
              }
              ?>
          </a>

          <h3 class="card__title"> <!-- título centralizado -->
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>

          <div class="card__actions"> <!-- ações -->
            <a class="btn btn--primary btn--sm" href="<?php the_permalink(); ?>">
              <?php esc_html_e('Ler mais','tema-05'); ?> <span aria-hidden="true">→</span>
            </a>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div> <!-- fim grid -->
  <?php endif; ?>
</section>

<?php
/* ===============================
 *  Seção: Categoria em Destaque (via Customizer)
 * =============================== */
$cat_id    = absint( get_theme_mod('t05_featured_cat', 0) ); // ID escolhido pelo cliente
$args_base = [
  'posts_per_page'      => 7,  // 1 destaque + 6 lista
  'ignore_sticky_posts' => true,
  'no_found_rows'       => true,
];

if ( $cat_id > 0 ) {
  $term         = get_term( $cat_id, 'category' );
  $titulo_secao = $term && !is_wp_error($term) ? $term->name : __('Destaques','tema-05');
  $q            = new WP_Query( $args_base + ['cat' => $cat_id] );
} else {
  $titulo_secao = __('Destaques','tema-05'); // fallback
  $q            = new WP_Query( $args_base );
}
?>

<?php if ($q->have_posts()): ?>
<section class="home-cat section">
  <h2 class="home-cat__title"><?php echo esc_html($titulo_secao); ?></h2>

  <div class="home-cat__grid"> <!-- 2 colunas: esquerda destaque | direita lista -->
    <!-- ESQUERDA (destaque) -->
    <div class="cat-left">
      <?php $q->the_post(); ?>
      <article <?php post_class('cat-left__card'); ?>>
        <a class="cat-left__thumb" href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) {
            the_post_thumbnail('t05-cat-feature', ['alt'=>esc_attr(get_the_title())]);
          } ?>
        </a>

        <?php $c = get_the_category(); if (!empty($c)): ?>
          <span class="post-tag post-tag--cat"><?php echo esc_html($c[0]->name); ?></span>
        <?php endif; ?>

        <h3 class="cat-left__title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="cat-left__actions">
          <a class="btn btn--primary btn--sm" href="<?php the_permalink(); ?>">
            <?php esc_html_e('Ler mais','tema-05'); ?> <span aria-hidden="true">→</span>
          </a>
        </div>
      </article>
    </div>

    <!-- DIREITA (lista 2x3, com divisor por linha) -->
    <div class="cat-right">
      <?php
        $items = [];
        while ($q->have_posts()) { $q->the_post(); $items[] = get_post(); }
        for ($row=0; $row<3; $row++):
          $pair = array_slice($items, $row*2, 2);
          if (empty($pair)) break;
      ?>
      <div class="cat-list__row"> <!-- divisor nesta linha -->
        <?php foreach ($pair as $p): setup_postdata($p); ?>
          <article <?php post_class('cat-list__item', $p->ID); ?>>
            <a class="cat-list__thumb" href="<?php echo esc_url(get_permalink($p)); ?>">
              <?php if (has_post_thumbnail($p)) {
                echo get_the_post_thumbnail($p, 't05-cat-list', ['alt'=>esc_attr(get_the_title($p))]);
              } ?>
            </a>

            <div class="cat-list__content">
              <?php $cc = get_the_category($p->ID); if (!empty($cc)): ?>
                <span class="post-tag post-tag--cat"><?php echo esc_html($cc[0]->name); ?></span>
              <?php endif; ?>

              <h4 class="cat-list__title">
                <a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a>
              </h4>

              <div class="cat-list__actions">
                <a class="btn btn--primary btn--sm" href="<?php echo esc_url(get_permalink($p)); ?>">
                  <?php esc_html_e('Ler mais','tema-05'); ?> <span aria-hidden="true">→</span>
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php wp_reset_postdata(); endif; ?>

<?php
// ===== Função que imprime UMA seção de categoria (reaproveita seu bloco atual)
function t05_print_home_cat_section($cat_id, $title_override = '') {

  $args_base = [
    'posts_per_page'      => 7,  // 1 destaque + 6 lista
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
  ];

  if ($cat_id > 0) {
    $term         = get_term($cat_id, 'category');
    $titulo_secao = $title_override !== '' ? $title_override :
                    ($term && !is_wp_error($term) ? $term->name : __('Destaques','tema-05'));
    $q            = new WP_Query($args_base + ['cat' => $cat_id]);
  } else {
    // fallback: posts recentes se nada foi escolhido
    $titulo_secao = $title_override !== '' ? $title_override : __('Destaques','tema-05');
    $q            = new WP_Query($args_base);
  }

  if ($q->have_posts()):
?>
<section class="home-cat section">
  <h2 class="home-cat__title"><?php echo esc_html($titulo_secao); ?></h2>
  <div class="home-cat__grid">
    <div class="cat-left">
      <?php $q->the_post(); ?>
      <article <?php post_class('cat-left__card'); ?>>
        <a class="cat-left__thumb" href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) {
            the_post_thumbnail('t05-cat-feature', ['alt'=>esc_attr(get_the_title())]);
          } ?>
        </a>
        <?php $c = get_the_category(); if (!empty($c)): ?>
          <span class="post-tag post-tag--cat"><?php echo esc_html($c[0]->name); ?></span>
        <?php endif; ?>
        <h3 class="cat-left__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="cat-left__actions">
          <a class="btn btn--primary btn--sm" href="<?php the_permalink(); ?>">
            <?php esc_html_e('Ler mais','tema-05'); ?> <span aria-hidden="true">→</span>
          </a>
        </div>
      </article>
    </div>

    <div class="cat-right">
      <?php
        $items = [];
        while ($q->have_posts()) { $q->the_post(); $items[] = get_post(); }
        for ($row=0; $row<3; $row++):
          $pair = array_slice($items, $row*2, 2);
          if (empty($pair)) break;
      ?>
      <div class="cat-list__row">
        <?php foreach ($pair as $p): setup_postdata($p); ?>
          <article <?php post_class('cat-list__item', $p->ID); ?>>
            <a class="cat-list__thumb" href="<?php echo esc_url(get_permalink($p)); ?>">
              <?php if (has_post_thumbnail($p)) {
                echo get_the_post_thumbnail($p, 't05-cat-list', ['alt'=>esc_attr(get_the_title($p))]);
              } ?>
            </a>
            <div class="cat-list__content">
              <?php $cc = get_the_category($p->ID); if (!empty($cc)): ?>
                <span class="post-tag post-tag--cat"><?php echo esc_html($cc[0]->name); ?></span>
              <?php endif; ?>
              <h4 class="cat-list__title"><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></h4>
              <div class="cat-list__actions">
                <a class="btn btn--primary btn--sm" href="<?php echo esc_url(get_permalink($p)); ?>">
                  <?php esc_html_e('Ler mais','tema-05'); ?> <span aria-hidden="true">→</span>
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php
  wp_reset_postdata();
  endif;
}
?>

<?php
// --- 2ª seção de categoria (vinda do Customizer)
$cat2   = absint( get_theme_mod('t05_featured_cat_2', 0) );
$title2 = (string) get_theme_mod('t05_featured_title_2', '' );

t05_print_home_cat_section($cat2, $title2);
?>

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


</main> <!-- fim main -->

<?php get_footer(); ?> <!-- footer -->

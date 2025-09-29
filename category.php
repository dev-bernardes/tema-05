<?php
/**
 * Template: Category – dobra (1 + 3) + grade (12/página) + paginação
 */
get_header();

$paged  = max(1, (int) get_query_var('paged'));
$term   = get_queried_object();
$cat_id = (int) ($term->term_id ?? 0);

/* ===== Página 1: dobra (1 destaque + 3 laterais) ===== */
if ($paged === 1) {
  $q_feature = new WP_Query([
    'cat' => $cat_id, 'posts_per_page' => 1, 'ignore_sticky_posts' => true,
  ]);
  $q_side = new WP_Query([
    'cat' => $cat_id, 'posts_per_page' => 3, 'offset' => 1, 'ignore_sticky_posts' => true,
  ]);
}

/* ===== Grade inferior: 12/página a partir do 5º post ===== */
$per_page_grade = 12;

// total da categoria
$q_count = new WP_Query([
  'cat' => $cat_id, 'posts_per_page' => 1, 'no_found_rows' => false, 'fields' => 'ids'
]);
$total_posts = (int) $q_count->found_posts;
wp_reset_postdata();

$already_showed = 4; // 1 destaque + 3 laterais
$rest_count  = max(0, $total_posts - $already_showed);
$total_pages = max(1, (int) ceil($rest_count / $per_page_grade));

// offset da grade
$offset_grade = ($paged === 1)
  ? $already_showed
  : $already_showed + ($paged - 2) * $per_page_grade;

$q_grade = new WP_Query([
  'cat' => $cat_id,
  'posts_per_page' => $per_page_grade,
  'offset' => $offset_grade,
  'ignore_sticky_posts' => true,
  'no_found_rows' => true,
]);
?>
<main class="cat-archive">
  <div class="cat-archive__layout">
    <!-- MENU LATERAL -->
    <aside class="cat-aside">
      <nav class="aside-menu"><ul>
        <?php wp_list_categories(['title_li'=>'','hide_empty'=>0,'show_count'=>0]); ?>
      </ul></nav>
    </aside>

    <!-- CONTEÚDO (apenas título + dobra) -->
    <section class="cat-content">
      <header class="archive-header">
        <h1 class="archive-title"><?php echo esc_html( single_cat_title('', false) ); ?></h1>
      </header>

      <?php if ($paged === 1): ?>
        <!-- ===== DOBRA (1 destaque 700px + 3 laterais 1 coluna) ===== -->
        <section class="fold">
          <div class="fold__grid">
            <div class="fold-left">
              <?php if ($q_feature && $q_feature->have_posts()): $q_feature->the_post(); ?>
                <article <?php post_class('card-feature'); ?>>
                  <a class="feature__thumb" href="<?php the_permalink(); ?>">
                    <?php has_post_thumbnail() && the_post_thumbnail('t05-cat-feature', ['alt'=>esc_attr(get_the_title())]); ?>
                  </a>
                  <?php $c=get_the_category(); if(!empty($c)): ?>
                    <span class="post-tag post-tag--cat"><?php echo esc_html($c[0]->name); ?></span>
                  <?php endif; ?>
                  <h2 class="feature__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                  <a class="btn btn--primary btn--sm" href="<?php the_permalink(); ?>">Ler mais <span aria-hidden="true">→</span></a>
                </article>
              <?php endif; wp_reset_postdata(); ?>
            </div>

            <div class="fold-right">
              <?php if ($q_side && $q_side->have_posts()): while ($q_side->have_posts()): $q_side->the_post(); ?>
                <article <?php post_class('cat-list__item'); ?>>
                  <a class="cat-list__thumb" href="<?php the_permalink(); ?>">
                    <?php has_post_thumbnail() && the_post_thumbnail('t05-cat-list', ['alt'=>esc_attr(get_the_title())]); ?>
                  </a>
                  <div class="cat-list__content">
                    <?php $cc=get_the_category(); if(!empty($cc)): ?>
                      <span class="post-tag post-tag--cat"><?php echo esc_html($cc[0]->name); ?></span>
                    <?php endif; ?>
                    <h3 class="cat-list__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <a class="btn btn--primary btn--sm" href="<?php the_permalink(); ?>">Ler mais <span aria-hidden="true">→</span></a>
                  </div>
                </article>
              <?php endwhile; endif; wp_reset_postdata(); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>
    </section><!-- /.cat-content -->

    <!-- ===== GRADE INFERIOR (12 por página) ===== -->
    <section class="archive-grid">
      <?php if ($q_grade->have_posts()): while ($q_grade->have_posts()): $q_grade->the_post(); ?>
        <article class="post-card">
          <a class="post-card__thumb" href="<?php the_permalink(); ?>">
            <?php has_post_thumbnail() && the_post_thumbnail('medium_large'); ?>
          </a>
          <div class="post-card__body">
            <?php $cg = get_the_category(); if(!empty($cg)): ?>
              <span class="post-tag post-tag--cat"><?php echo esc_html($cg[0]->name); ?></span>
            <?php endif; ?>
            <h3 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <a class="btn btn--primary btn--sm" href="<?php the_permalink(); ?>">Ler mais <span aria-hidden="true">→</span></a>
          </div>
        </article>
      <?php endwhile; else: ?>
        <p>Sem mais posts.</p>
      <?php endif; wp_reset_postdata(); ?>
    </section>

    <!-- Divisor -->
    <div class="archive-sep" aria-hidden="true"></div>

    <!-- ===== Paginação completa ===== -->
    <?php
      $base   = get_pagenum_link(1) . '%_%';
      $format = ( get_option('permalink_structure') ) ? 'page/%#%/' : '&paged=%#%';
      $numbers = paginate_links([
        'base'      => $base,
        'format'    => $format,
        'current'   => $paged,
        'total'     => $total_pages,
        'mid_size'  => 2,
        'end_size'  => 1,
        'prev_next' => false,
        'type'      => 'plain',
      ]);
      $prev_url = $paged > 1 ? get_pagenum_link($paged - 1) : '';
      $next_url = $paged < $total_pages ? get_pagenum_link($paged + 1) : '';
    ?>
    <nav class="pagination-full" role="navigation" aria-label="Paginação">
      <div class="pag-prev">
        <?php if ($prev_url): ?>
          <a class="pag-btn" href="<?php echo esc_url($prev_url); ?>">← Anterior</a>
        <?php else: ?>
          <span class="pag-btn is-disabled">← Anterior</span>
        <?php endif; ?>
      </div>

      <div class="pag-numbers">
        <?php echo $numbers; // já vem com .page-numbers ?>
      </div>

      <div class="pag-next">
        <?php if ($next_url): ?>
          <a class="pag-btn" href="<?php echo esc_url($next_url); ?>">Próxima página →</a>
        <?php else: ?>
          <span class="pag-btn is-disabled">Próxima página →</span>
        <?php endif; ?>
      </div>
    </nav>
  </div>

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

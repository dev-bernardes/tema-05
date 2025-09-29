<?php
get_header();

$paged  = max(1, (int) get_query_var('paged'));
$term   = get_queried_object();
$cat_id = (int) ($term->term_id ?? 0);

// Página 1: 1 destaque + 3 laterais (1 coluna)
if ($paged === 1) {
  $q_feature = new WP_Query([
    'cat' => $cat_id, 'posts_per_page' => 1, 'ignore_sticky_posts' => true,
  ]);
  $q_side = new WP_Query([
    'cat' => $cat_id, 'posts_per_page' => 3, 'offset' => 1, 'ignore_sticky_posts' => true,
  ]);
}

// Grade inferior
$per_page_grade = 9;
$q_count = new WP_Query(['cat'=>$cat_id,'posts_per_page'=>1,'no_found_rows'=>false,'fields'=>'ids']);
$total_posts = (int) $q_count->found_posts; wp_reset_postdata();
$rest_count  = max(0, $total_posts - 4); // 1+3 já usados
$total_pages = max(1, (int) ceil($rest_count / $per_page_grade));
$offset_grade = ($paged === 1) ? 4 : 4 + ($paged-2)*$per_page_grade;

$q_grade = new WP_Query([
  'cat'=>$cat_id,'posts_per_page'=>$per_page_grade,'offset'=>$offset_grade,
  'ignore_sticky_posts'=>true,'no_found_rows'=>true
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

    <!-- CONTEÚDO (largura máx 1380) -->
    <section class="cat-content">
      <header class="archive-header">
        <h1 class="archive-title"><?php echo esc_html( single_cat_title('', false) ); ?></h1>
      </header>

      <?php if ($paged === 1): ?>
      <section class="fold">
        <div class="fold__grid">
          <!-- Destaque 700px -->
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

          <!-- Lateral: 1 coluna, 3 posts -->
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

      <!-- Grade inferior -->
      <section class="archive-grid">
        <?php if ($q_grade->have_posts()): while ($q_grade->have_posts()): $q_grade->the_post(); ?>
          <article class="post-card">
            <a class="post-card__thumb" href="<?php the_permalink(); ?>">
              <?php has_post_thumbnail() && the_post_thumbnail('medium_large'); ?>
            </a>
            <div class="post-card__body">
              <h3 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            </div>
          </article>
        <?php endwhile; else: ?><p>Sem mais posts.</p><?php endif; wp_reset_postdata(); ?>
      </section>

      <nav class="pagination-wrap pagination">
        <?php echo paginate_links([
          'current'=>$paged, 'total'=>$total_pages, 'mid_size'=>2,
          'prev_text'=>'← Anterior', 'next_text'=>'Próxima →',
        ]); ?>
      </nav>
    </section>
  </div>
</main>
<?php get_footer(); ?>

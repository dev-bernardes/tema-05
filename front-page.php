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

          <a class="card__thumb" href="<?php the_permalink(); ?>"> <!-- imagem -->
            <?php
              // Use um size registrado (ex: t05-card) ou fallback para 'medium_large'
              if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'medium_large', ['alt' => esc_attr( get_the_title() )] );
              } else {
                // opcional: placeholder
                echo '<img src="'.esc_url( get_template_directory_uri().'/assets/img/placeholder-4x3.jpg' ).'" alt="'.esc_attr( get_the_title() ).'">';
              }
            ?>
          </a>

          <?php $cat = get_the_category(); if ( ! empty( $cat ) ): ?>
            <span class="post-tag post-tag--recent"> <!-- chip de categoria -->
              <?php echo esc_html( $cat[0]->name ); ?>
            </span>
          <?php endif; ?>

          <h3 class="card__title card__title--center"> <!-- título centralizado -->
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


</main> <!-- fim main -->

<?php get_footer(); ?> <!-- footer -->

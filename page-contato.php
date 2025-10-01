<?php
/**
 * Template Name: Página de Contato (Tema 05)
 * Description: Layout com aside 186px, gap 55px, conteúdo 1360px (700px texto + form).
 */
get_header();
?>

<main class="contact-page" role="main">
  <div class="contact__wrap">

    <!-- ASIDE (TÓPICOS) -->
    <aside class="contact__aside" aria-label="Menu lateral">
      <ul class="contact__toc">
        <?php for($i=1; $i<=11; $i++): ?>
          <li><a href="#"><?php echo 'TÓPICO '.$i; ?></a></li>
        <?php endfor; ?>
      </ul>
    </aside>

    <!-- GAP fixo de 55px é a 2ª coluna do grid -->

    <!-- CONTEÚDO (1360px) -->
    <section class="contact__content" aria-labelledby="contact-title">
      <h1 id="contact-title" class="contact__title">Entre em contato</h1>

      <div class="contact__grid">
        <!-- Coluna esquerda (700px) -->
        <article class="contact__card">
          <?php if (have_posts()): while (have_posts()): the_post(); ?>
            <?php
              // Intro e corpo vindos do editor, para o texto do mock
              the_content();
            ?>
          <?php endwhile; endif; ?>
        </article>

        <!-- Coluna direita (formulário) -->
        <form class="contact__form" action="#" method="post" novalidate>
          <?php wp_nonce_field('t05_contact','t05_contact_nonce'); ?>

          <div class="field">
            <label class="sr-only" for="c-name">Nome</label>
            <input id="c-name" type="text" name="name" placeholder="Digite seu nome" required>
          </div>

          <div class="field">
            <label class="sr-only" for="c-email">E-mail</label>
            <input id="c-email" type="email" name="email" placeholder="Digite seu melhor e-mail" required>
          </div>

          <div class="field">
            <label class="sr-only" for="c-msg">Mensagem</label>
            <textarea id="c-msg" name="message" placeholder="Digite sua mensagem..." required></textarea>
          </div>

          <button class="contact__submit" type="submit">ENVIAR</button>
        </form>
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

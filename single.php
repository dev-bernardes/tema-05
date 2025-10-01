<?php
/**
 * Template: Post único – meta (data • leitura) + título + thumb + conteúdo com ADS por parágrafo
 * Sidebar: lista com 6 posts
 */
get_header();

/** === helpers simples (fallback) === */
if (!function_exists('t05_reading_time')) {
  function t05_reading_time($post_id = null, $wpm = 200) {
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field('post_content', $post_id);
    $words   = str_word_count( wp_strip_all_tags($content) );
    $mins    = max(1, ceil($words / max(120, (int)$wpm)));
    return $mins . ' minutos de leitura';
  }
}

/** injeta bloco ADS após cada </p> no conteúdo já filtrado */
function t05_inject_ads_blocks($html) {
  $ads = '<div class="ads-block" role="complementary" aria-label="Publicidade">ADS</div>';
  $html = preg_replace('#</p>\s*#i', '</p>' . $ads, $html);
  return $html;
}
?>

<main class="cat-archive"><!-- reaproveitando grid/base -->
  <div class="cat-archive__layout">
    <!-- MENU LATERAL (alinhado ao header) -->
    <aside class="cat-aside">
      <nav class="aside-menu">
        <ul><?php wp_list_categories(['title_li'=>'','hide_empty'=>0,'show_count'=>0]); ?></ul>
      </nav>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <section class="post-main">
      <?php if (have_posts()): while (have_posts()): the_post(); ?>
        <article <?php post_class('post-article'); ?>>

          <!-- Meta: data • tempo de leitura -->
          <div class="post-meta">
            <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>">
              <?php echo esc_html( get_the_date('d \d\e F \d\e Y') ); ?>
            </time>
            <span class="dot" aria-hidden="true">•</span>
            <span class="readtime"><?php echo esc_html( t05_reading_time() ); ?></span>
          </div>

          <!-- Título -->
          <h1 class="post-title"><?php the_title(); ?></h1>

          <!-- Hero: thumb (680x284) + barra vertical de compartilhamento -->
          <div class="post-hero">
            <?php if (has_post_thumbnail()): ?>
              <figure class="post-thumb">
                <?php the_post_thumbnail('t05-single-hero', ['alt'=>esc_attr(get_the_title())]); ?>
              </figure>
            <?php endif; ?>

            <?php
              $share_url   = urlencode( get_permalink() );
              $share_title = urlencode( get_the_title() );
            ?>
            <ul class="post-share">
              <li><a class="ps ps--x"  href="https://twitter.com/intent/tweet?url=<?=$share_url?>&text=<?=$share_title?>" target="_blank" rel="nofollow noopener" aria-label="Compartilhar no X">X</a></li>
              <li><a class="ps ps--ig" href="#" data-copy="<?php echo esc_url( get_permalink() ); ?>" aria-label="Copiar link (Instagram)">IG</a></li>
              <li><a class="ps ps--wa" href="https://api.whatsapp.com/send?text=<?=$share_title?>%20<?=$share_url?>" target="_blank" rel="nofollow noopener" aria-label="Enviar no WhatsApp">WA</a></li>
              <li><a class="ps ps--fb" href="https://www.facebook.com/sharer/sharer.php?u=<?=$share_url?>" target="_blank" rel="nofollow noopener" aria-label="Compartilhar no Facebook">FB</a></li>
            </ul>
          </div>

          <!-- Conteúdo com ADS a cada parágrafo -->
          <div class="post-content">
            <?php
              $content = apply_filters('the_content', get_the_content());
              echo t05_inject_ads_blocks($content);
            ?>
          </div>

                    <!-- ===== Fim do conteúdo: Share + Autor ===== -->
          <section class="post-end">
            <?php
              $share_url   = urlencode( get_permalink() );
              $share_title = urlencode( get_the_title() );
            ?>
            <!-- Share column -->
            <div class="post-end__share">
              <ul class="post-end__rail">
                <li><a class="bps bps--x"  href="https://twitter.com/intent/tweet?url=<?=$share_url?>&text=<?=$share_title?>" target="_blank" rel="nofollow noopener" aria-label="Compartilhar no X">X</a></li>
                <li><a class="bps bps--ig" href="#" data-copy="<?php echo esc_url( get_permalink() ); ?>" aria-label="Copiar link (Instagram)">IG</a></li>
                <li><a class="bps bps--wa" href="https://api.whatsapp.com/send?text=<?=$share_title?>%20<?=$share_url?>" target="_blank" rel="nofollow noopener" aria-label="Enviar no WhatsApp">WA</a></li>
                <li><a class="bps bps--fb" href="https://www.facebook.com/sharer/sharer.php?u=<?=$share_url?>" target="_blank" rel="nofollow noopener" aria-label="Compartilhar no Facebook">FB</a></li>
              </ul>
            </div>

            <!-- Author card -->
            <?php
              $aid   = (int) get_the_author_meta('ID');
              $name  = get_the_author();
              $bio   = get_the_author_meta('description');
              $alink = get_author_posts_url($aid);
            ?>
            <aside class="post-author" itemscope itemtype="https://schema.org/Person">
              <div class="post-author__inner">
                <div class="post-author__avatar">
                  <?php echo get_avatar($aid, 140, '', $name, ['class'=>'post-author__img','extra_attr'=>'itemprop="image"']); ?>
                </div>
                <div class="post-author__body">
                  <h3 class="post-author__kicker">Sobre o autor</h3>
                  <h2 class="post-author__name" itemprop="name">
                    <a href="<?php echo esc_url($alink); ?>"><?php echo esc_html($name); ?></a>
                  </h2>
                  <?php if ($bio): ?>
                    <p class="post-author__bio" itemprop="description"><?php echo esc_html($bio); ?></p>
                  <?php else: ?>
                    <p class="post-author__bio">Este é o perfil do autor. Edite sua biografia no painel em <em>Usuários → Perfil</em>.</p>
                  <?php endif; ?>
                </div>
              </div>
            </aside>
          </section>
          <!-- ===== /Fim: Share + Autor ===== -->

        </article>
      <?php endwhile; endif; ?>
    </section>

    <!-- SIDEBAR DIREITA – SOMENTE LISTA (6 itens) -->
    <aside class="post-sidebar">
      <?php
        $cats = get_the_category();
        $rel_cat_id = (!empty($cats)) ? (int)$cats[0]->term_id : 0;

        $q_list = new WP_Query([
          'cat' => $rel_cat_id,
          'posts_per_page' => 6,
          'post__not_in' => [get_the_ID()],
          'ignore_sticky_posts' => true,
        ]);
      ?>

      <div class="side-list side-list--only">
        <?php if ($q_list->have_posts()): while ($q_list->have_posts()): $q_list->the_post(); ?>
          <article <?php post_class('cat-list__item cat-list__item--side'); ?>>
            <a class="cat-list__thumb" href="<?php the_permalink(); ?>">
              <?php has_post_thumbnail() && the_post_thumbnail('t05-cat-list', ['alt'=>esc_attr(get_the_title())]); ?>
            </a>
            <div class="cat-list__content">
              <?php $cc=get_the_category(); if(!empty($cc)): ?>
                <span class="post-tag post-tag--cat"><?php echo esc_html($cc[0]->name); ?></span>
              <?php endif; ?>
              <h3 class="cat-list__title cat-list__title--16">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h3>
              <a class="btn btn--primary btn--sm" href="<?php the_permalink(); ?>">Ler mais <span aria-hidden="true">→</span></a>
            </div>
          </article>
        <?php endwhile; wp_reset_postdata(); endif; ?>
      </div>
    </aside>
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

                
  <!-- divisor opcional após artigo -->
  <div class="archive-sep" aria-hidden="true"></div>

  <!-- Copiar link (IG) -->
  <script>
    document.addEventListener('click', (e)=>{
      const a = e.target.closest('.ps--ig');
      if(!a) return;
      e.preventDefault();
      const link = a.getAttribute('data-copy');
      if (navigator.clipboard) {
        navigator.clipboard.writeText(link).then(()=>{
          const txt = a.textContent;
          a.textContent='Copiado';
          setTimeout(()=>a.textContent=txt,1500);
        });
      } else {
        prompt('Copie o link:', link);
      }
    });
  </script>
</main>

<?php get_footer(); ?>

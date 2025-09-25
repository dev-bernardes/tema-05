<?php get_header(); ?> <!-- chama o header.php -->
<main id="content"> <!-- conteúdo principal -->
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?> <!-- loop principal: percorre posts -->
        <article <?php post_class('post-card'); ?>>
            <a href="php the_permalink(); ?>"> <!-- link para o post -->
                <h2 class ="post-title">
                    <?php the_title(); ?> <!-- título do post -->
                </h2>
            </a> <!-- fecha link do post -->
            <div class="post-meta">
                <?php echo esc_html(get_the_date()); ?> <!-- data do post -->
            </div>
            <div class="post-excerpt">
                <?php the_excerpt(); ?> <!-- resumo do post -->
            </div>
        </article> <!-- fim do article -->
    <?php endwhile; else : ?> <!-- se não houver posts -->
        <p><?php esc_html_e('Nenhum post encontrado.', 'theme-05'); ?></p> <!-- mensagem de nenhum post encontrado -->
    <?php endif; ?> <!-- fim do loop -->
</main> <!-- fim do main -->
<?php get_footer(); ?>
                
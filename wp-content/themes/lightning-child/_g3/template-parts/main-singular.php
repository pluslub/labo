<?php
/**
 * Singular main template
 *
 * @package Lightning G3
 */


if ( apply_filters( 'lightning_is_extend_single', false ) ) :
    do_action( 'lightning_extend_single' );
else :
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            $client      = get_post_meta( get_the_ID(), 'client_name', true );
            $description = get_post_meta( get_the_ID(), 'project_desc', true );
            ?>

            <!-- 1段目：タイトル＋説明文 横並び -->
            <div class="portfolio-detail-container">
                <div class="portfolio-detail-left">
                    <h1 class="entry-title typesquare_option">
                        <?php the_title(); ?>
                    </h1>
                </div>
                <div class="portfolio-detail-right">
                    <?php if ( ! empty( $client ) ) : ?>
                        <div class="project-client-name"><?php echo esc_html( $client ); ?></div>
                    <?php endif; ?>
                    <?php if ( ! empty( $description ) ) : ?>
                        <div class="project-description-text"><?php echo nl2br( esc_html( $description ) ); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2段目・3段目：アイキャッチ＋コンテンツ（Lightning標準） -->
            <?php
            $template = 'template-parts/entry-' . esc_attr( $post->post_name ) . '.php';
            $return   = locate_template( $template );
            if ( $return && get_post_type() !== $post->post_name ) {
                locate_template( $template, true );
            } else {
                lightning_get_template_part( 'template-parts/entry', get_post_type() );
            }
            if ( apply_filters( 'lightning_is_next_prev', is_single(), 'next_prev' ) ) {
                lightning_get_template_part( 'template-parts/next-prev', get_post_type() );
            }
        endwhile;
    endif;
endif;
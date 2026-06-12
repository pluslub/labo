<?php
/**
 * newsカテゴリー専用テンプレート
 * 
 */

get_header(); ?>

<div class="site-body-container container">
  <div class="main-section" id="main" role="main">

    <!-- ヘッダー -->
    <div class="portfolio-header">
      <h1 class="entry-title portfolio-detail-container"><?php single_cat_title(); ?></h1>
      <?php
        $category_description = category_description();
        if ( ! empty( $category_description ) ) {
          echo '<div class="portfolio-description">' . wp_kses_post( $category_description ) . '</div>';
        }
      ?>
    </div>
    <!-- グリッド -->
    <?php if ( have_posts() ) : ?>
      <div class="portfolio-grid">
        <?php while ( have_posts() ) : the_post(); ?>
          <div class="portfolio-grid-item">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium_large' ); ?>
              <?php else : ?>
                <div class="no-thumbnail">No Image</div>
              <?php endif; ?>
            </a>
          </div>
        <?php endwhile; ?>
      </div>

      <?php
        if ( is_tag() ) {
          $big = 999999999;
          echo paginate_links( array(
            'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'  => '?paged=%#%',
            'current' => max( 1, get_query_var( 'paged' ) ),
            'total'   => $GLOBALS['wp_query']->max_num_pages,
          ) );
        } else {
          the_posts_pagination();
        }
      ?>

    <?php else : ?>
      <p class="no-posts">最新記事がありません。</p>
    <?php endif; ?>

  </div><!-- /.main-section -->
</div><!-- /.site-body-container -->

<?php get_footer(); ?>

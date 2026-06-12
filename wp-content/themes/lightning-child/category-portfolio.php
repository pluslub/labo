<?php
/**
 * カテゴリ「portfolio」専用テンプレート
 * ポートフォリオギャラリー表示
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

    <!-- タグフィルター -->
    <?php
      $current_tag = isset( $_GET['tag'] ) ? sanitize_title( $_GET['tag'] ) : '';
      $tags_in_category = get_tags( array( 'hide_empty' => true ) );
      if ( $tags_in_category ) :
    ?>
    <div class="portfolio-filter">
      <a href="<?php echo esc_url( get_category_link( get_queried_object_id() ) ); ?>"
         class="<?php echo $current_tag === '' ? 'active' : ''; ?>">すべて</a>
      <?php foreach ( $tags_in_category as $tag ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'tag', $tag->slug, get_category_link( get_queried_object_id() ) ) ); ?>"
           class="<?php echo $current_tag === $tag->slug ? 'active' : ''; ?>">
          <?php echo esc_html( $tag->name ); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- グリッド -->
    <?php
      if ( $current_tag ) {
        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
        $filtered_query = new WP_Query( array(
          'cat'            => get_queried_object_id(),
          'tag'            => $current_tag,
          'paged'          => $paged,
          'posts_per_page' => get_option( 'posts_per_page' ),
          'orderby'        => 'date',
          'order'          => 'DESC',
          ) );
        $the_query = $filtered_query;
      } else {
        global $wp_query;
        $the_query = $wp_query;
      }
    ?>

    <?php if ( $the_query->have_posts() ) : ?>
      <div class="portfolio-grid">
        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
          <div class="portfolio-grid-item">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium_large' ); ?>
              <?php else : ?>
                <div class="no-thumbnail">No Image</div>
              <?php endif; ?>
            </a>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>

      <?php
        if ( $current_tag ) {
          $big = 999999999;
          echo paginate_links( array(
            'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'  => '?paged=%#%',
            'current' => max( 1, get_query_var( 'paged' ) ),
            'total'   => $the_query->max_num_pages,
          ) );
        } else {
          the_posts_pagination();
        }
      ?>

    <?php else : ?>
      <p class="no-posts">ポートフォリオがありません。</p>
    <?php endif; ?>

  </div><!-- /.main-section -->
</div><!-- /.site-body-container -->

<?php get_footer(); ?>

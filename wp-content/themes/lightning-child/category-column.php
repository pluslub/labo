<?php
/**
 * コラムサイトのWordPressテーマ
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
    <div class="portfolio-intro">
    <h2>はなれていても、近くにいる</h2>
      <p>この仕事をしていると、その人の生い立ちや価値観、苦労や苦悩をお聞かせいただくことが多いです。<br>
      それらを聞いていると、「私がこの人でもおかしくなかった」と思うことばかりで、とても複雑な気持ちになります。</p>

      <p>このコラムで紹介するのは、“特別な誰か”ではなく、あなたや私が、ほんの少し違う条件で生きていたら――<br>
      同じ場所に立っていたかもしれない人たちのお話です。</p>

      <p>「もし自分がその立場だったら？」と想像してみる。 “無知のヴェール”を少しだけかぶって、目の前の人の現実を覗いてみる。<br>
      それが、社会を変える最初の一歩かもしれません。</p>

      <p>そして、一方で。どんなにシリアスな現実にも、ユーモアはちゃんと息づいている。 <br>
      人は、笑いながら考えることができる生き物です。</p>

      <p>――私たちの世界は、良い世界でしょうか。それとも、まだ“もう少しマシになれる”世界でしょうか。</p>

      <p>しらんけど。</p>

      <p>編集担当<br>中塚祐起</p>
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
      <p class="no-posts">コラム記事がありません。</p>
    <?php endif; ?>

  </div><!-- /.main-section -->
</div><!-- /.site-body-container -->

<?php get_footer(); ?>

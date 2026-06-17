<?php
/**
 * Lightning Footer Template
 */
?>
<footer class="l-footer">
    <div class="l-footer__inner">
        <div class="l-footer__top">
            <a href="https://pluslab.wakatake.info/index.html" class="l-footer__logo l-footer__logo--pc" aria-label="Plusらぼ トップへ">
                <img src="<?php echo esc_url( home_url( '/assets/images/logo_01_white.png' ) ); ?>" alt="" class="l-footer__logo-img">
            </a>
                <nav class="l-footer__nav" aria-label="フッターナビゲーション">
                    <ul class="l-footer__nav-list">
                        <li><a href="https://pluslab.wakatake.info/index.html" class="l-footer__nav-link">トップ</a></li>
                        <li><a href="https://www.wakatake.info/" class="l-footer__nav-link">法人サイト</a></li>
                        <li><a href="https://pluslab.wakatake.info/about.html" class="l-footer__nav-link">Plusらぼについて</a></li>
                    </ul>
                    <ul class="l-footer__nav-list">
                        <li><a href="https://pluslab.wakatake.info/service-business.html" class="l-footer__nav-link">開発業務について</a></li>
                        <li><a href="https://pluslab.wakatake.info/lab-column" class="l-footer__nav-link">コラム</a></li>
                        <li><a href="https://pluslab.wakatake.info/archives/category/news" class="l-footer__nav-link">お知らせ</a></li>
                        <li><a href="https://pluslab.wakatake.info/contact.html" class="l-footer__nav-link">お問い合わせ</a></li>
                    </ul>
                </nav>
        </div>
        <div class="l-footer__bottom">
            <address class="l-footer__address">
                〒525-0022<br>
                滋賀県草津市川原町298-1 2F<br>
                tel：<a href="tel:0775695697">077-569-5697</a><br>
            </address>
            <div class="l-footer__legal">
                <a href="https://pluslab.wakatake.info/privacy.html" class="l-footer__privacy">プライバシーポリシー</a>
                <small class="l-footer__copy">©2026　Plusらぼ</small>
            </div>
        </div>
        <!-- SP専用ロゴ（最下部） -->
        <a href="https://pluslab.wakatake.info/index.html" class="l-footer__logo l-footer__logo--sp" aria-hidden="true" tabindex="-1">
            <img src="<?php echo esc_url( home_url( '/assets/images/logo_01_white.png' ) ); ?>" alt="" class="l-footer__logo-img">
        </a>
    </div>
</footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.14/dist/lenis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<script src="<?php echo home_url( '/js/main.js?v=20260605' ); ?>"></script>
	<script src="<?php echo home_url( 'js/service-work.js?v=20260605b' ); ?>"></script>
  
    <?php wp_footer(); ?>
</body>
</html>
<?php
/**
 * Lightning Footer Template
 */
?>
<div class="custom-cta-container">
    <div class="custom-cta-inner">
        
        <div class="custom-cta-image">
            <img src="http://xs888688.xsrv.jp/pluslab-test/wp-content/uploads/2026/05/Scene.png" alt="ご相談イラスト">
        </div>
        
        <div class="custom-cta-content">
            <h2 class="custom-cta-title">ちょっとしたことでも<br>ご相談ください</h2>
            <p class="custom-cta-text">
                気になることや、体験に関するご相談は常時受け付けておりますので、遠慮なくお問い合わせください。
            </p>
            
            <a href="/contact/" class="custom-cta-button">
                <span class="custom-cta-button-text">話を聞いてみる</span>
                <span class="custom-cta-button-arrow">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </a>
        </div>

    </div>
</div>
<footer class="custom-footer-wrapper">
    <div class="container site-footer-content">
        <div class="custom-footer-main">
            <div class="footer-column-info">
                <div class="footer-logo">
                    <img src="http://xs888688.xsrv.jp/pluslab-test/wp-content/uploads/2026/05/logo_footer.svg" alt="Plus lab">
                </div>
                <p class="footer-address">
                    〒525-0022<br>
                    滋賀県草津市川原町298-1 2F<br>
                    tel：077-569-5697<br>
                    mail：example@mail.com
                </p>
            </div>

            <div class="footer-column-menu">
                <nav class="footer-nav-row1">
                    <a href="https://www.wakatake.info/">法人サイト</a>
                    <a href="<?php echo esc_url( home_url( '/sample-page/' ) ); ?>">Plusらぼについて</a>
                    <a href="<?php echo esc_url( home_url( '/sitemap/' ) ); ?>">障害福祉サービスについて</a>
                </nav>
                <nav class="footer-nav-row2">
                    <a href="<?php echo esc_url( home_url( '/開発業務について/' ) ); ?>">開発業務について</a>
                    <a href="<?php echo esc_url( home_url( '/category/column/' ) ); ?>">コラム</a>
                    <a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>">お知らせ</a>
                    <a href="<?php echo esc_url( home_url( '/お問い合わせ/' ) ); ?>">お問い合わせ</a>
                </nav>
                <nav class="footer-nav-row3">
                    <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">プライバシーポリシー</a>
                    <span class="footer-copy">©2026 plus lab</span>
                </nav>
            </div>
        </div>
    </div>
</footer>

<?php 
/* * 重要：Lightningの標準フッター部品を無効化しつつ、
 * システムに必要なwp_footerだけを残す構成
 */
?>
</div><?php wp_footer(); ?>
</body>
</html>
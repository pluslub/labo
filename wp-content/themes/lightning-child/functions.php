<?php
/**
 * Lightning Child theme functions
 *
 * @package lightning
 */

/************************************************
 * 独自CSSファイルの読み込み処理
 *
 * 主に CSS を SASS で 書きたい人用です。 素の CSS を直接書くなら style.css に記載してかまいません.
 */

// 独自のCSSファイル（assets/css/）を読み込む場合は true に変更してください.
$my_lightning_additional_css = false;

if ( $my_lightning_additional_css ) {
	// 公開画面側のCSSの読み込み.
	add_action(
		'wp_enqueue_scripts',
		function() {
			wp_enqueue_style(
				'my-lightning-custom',
				get_stylesheet_directory_uri() . '/assets/css/style.css',
				array( 'lightning-design-style' ),
				filemtime( dirname( __FILE__ ) . '/assets/css/style.css' )
			);
		}
	);
	// 編集画面側のCSSの読み込み.
	add_action(
		'enqueue_block_editor_assets',
		function() {
			wp_enqueue_style(
				'my-lightning-editor-custom',
				get_stylesheet_directory_uri() . '/assets/css/editor.css',
				array( 'wp-edit-blocks', 'lightning-gutenberg-editor' ),
				filemtime( dirname( __FILE__ ) . '/assets/css/editor.css' )
			);
		}
	);
}

/************************************************
 * 独自の処理を必要に応じて書き足します
 */
// SVGファイルのアップロードを許可する
function my_upload_mimes($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
}
add_action('upload_mimes', 'my_upload_mimes');

// 前後記事の表示オプションを一括変更
add_filter( 'lightning_next_prev_options', function( $options ) {
    $options['layout']        = 'card';  // 標準的なカード型に変更
    $options['display_title'] = false;   // タイトルは非表示
    $options['display_date']  = false;   // 日付は非表示
    return $options;
});

// 「前の記事」のテキストを (前へ) に変更
add_filter( 'lightning_next_prev_options_prev', function( $options ) {
    $options['overlay'] = '<span class="custom-next-prev-label"></span>';
    return $options;
});

// 「次の記事」のテキストを (次へ) に変更
add_filter( 'lightning_next_prev_options_next', function( $options ) {
    $options['overlay'] = '<span class="custom-next-prev-label"></span>';
    return $options;
});
/* ==================================================
   投稿にカスタムフィールド（クライアント名・説明）を追加
================================================== */

// 1. 管理画面の投稿編集ページに入力枠（メタボックス）を作る
function add_custom_project_meta_box() {
    add_meta_box(
        'custom_project_info_box',    // メタボックスのID
        '実績の詳細情報設定',          // 管理画面に表示されるタイトル
        'render_custom_project_box',  // 表示用の中身を作る関数（下で定義）
        'post',                       // 投稿（post）に表示
        'normal',                     // 表示位置
        'high'                        // 優先度
    );
}
add_action('add_meta_boxes', 'add_custom_project_meta_box');

// 2. 入力枠のHTML（中身）を出力する
function render_custom_project_box($post) {
    // 既存のデータを取得（クライアント名: 'client_name'、説明: 'project_desc'）
    $client_name = get_post_meta($post->ID, 'client_name', true);
    $project_desc = get_post_meta($post->ID, 'project_desc', true);
    
    // セキュリティ用のトークン（ノンス）
    wp_nonce_field('save_custom_project_nonce', 'custom_project_nonce');
    ?>
    
    <p style="margin-bottom: 15px;">
        <label for="client_name_field" style="display:block; font-weight:bold; margin-bottom:5px;">クライアント名</label>
        <input type="text" id="client_name_field" name="client_name_field" value="<?php echo esc_attr($client_name); ?>" style="width:100%; max-width:500px; padding:8px; font-size:15px;">
    </p>
    
    <p>
        <label for="project_desc_field" style="display:block; font-weight:bold; margin-bottom:5px;">説明</label>
        <textarea id="project_desc_field" name="project_desc_field" rows="4" style="width:100%; max-width:500px; padding:8px; font-size:15px;"><?php echo esc_textarea($project_desc); ?></textarea>
    </p>
    
    <?php
}

// 3. 記事が保存（更新）されたときに、2つの値をデータベースに保存する
function save_custom_project_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['custom_project_nonce']) || !wp_verify_nonce($_POST['custom_project_nonce'], 'save_custom_project_nonce')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // クライアント名の保存処理
    if (isset($_POST['client_name_field'])) {
        $client_data = sanitize_text_field($_POST['client_name_field']);
        if ($client_data !== '') {
            update_post_meta($post_id, 'client_name', $client_data);
        } else {
            delete_post_meta($post_id, 'client_name');
        }
    }

    // 説明の保存処理（改行を許可するため sanitize_textarea_field を使用）
    if (isset($_POST['project_desc_field'])) {
        $desc_data = sanitize_textarea_field($_POST['project_desc_field']);
        if ($desc_data !== '') {
            update_post_meta($post_id, 'project_desc', $desc_data);
        } else {
            delete_post_meta($post_id, 'project_desc');
        }
    }
}
add_action('save_post', 'save_custom_project_meta_box');

function insert_google_ads_tags() {
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17541451522"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      // 💡全ページでGoogle広告（AW-）のベースコードを動かす（リマーケティング用）
      gtag('config', 'AW-17541451522');
    </script>
    <?php

    // 💡お問い合わせ完了ページ（スラッグが 'thanks' の場合）のみ、コンバージョンイベントを実行
    if ( is_page('thanks') ) { 
        ?>
        <script>
          gtag('event', 'generate_lead', {
              'event_category': 'contact',
              'event_label': 'form_submit'
          });
        </script>
        <?php
    }
}
add_action('wp_head', 'insert_google_ads_tags');
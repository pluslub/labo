<?php
/**
 * お問い合わせフォーム 送信処理
 * contact.html → contact-send.php → contact-thanks.html
 */
require_once __DIR__ . '/wp-load.php';

// GET・直接アクセスはフォームへ戻す
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wp_redirect(home_url('/contact.html'));
    exit;
}

// 入力値取得・サニタイズ
$name     = sanitize_text_field($_POST['name']    ?? '');
$tel      = sanitize_text_field($_POST['tel']     ?? '');
$email    = sanitize_email($_POST['email']        ?? '');
$message  = sanitize_textarea_field($_POST['message'] ?? '');
$honeypot = $_POST['website'] ?? '';

// ハニーポットにデータ → スパム。完了に見せかけて終了
if (!empty($honeypot)) {
    wp_redirect(home_url('/contact-thanks.html'));
    exit;
}

// バリデーション（必須項目・メール形式）
if (empty($name) || empty($email) || !is_email($email) || empty($message)) {
    wp_redirect(home_url('/contact.html?error=1'));
    exit;
}

// ★ 送信先アドレスをここに設定してください
$admin_email = 'pluslab.wakatake@gmail.com';
$site_name   = 'Plusらぼ';
$site_url    = 'https://pluslab.wakatake.info/';

// 管理者宛メール本文
$tel_text      = !empty($tel) ? $tel : '（未入力）';
$admin_subject = "【{$site_name}】お問い合わせがありました";
$admin_body    = <<<EOT
お問い合わせがありました。

━━━━━━━━━━━━━━━━━━━━
お名前：{$name}
電話番号：{$tel_text}
メールアドレス：{$email}
━━━━━━━━━━━━━━━━━━━━
お問い合わせ内容：

{$message}
━━━━━━━━━━━━━━━━━━━━
EOT;
$admin_headers = [
    'Content-Type: text/plain; charset=UTF-8',
    "Reply-To: {$email}",
];

// 送信者への自動返信メール本文
$auto_subject = "【{$site_name}】お問い合わせを受け付けました";
$auto_body    = <<<EOT
{$name} 様

お問い合わせいただきありがとうございます。
内容を確認の上、担当者よりご連絡いたします。
今しばらくお待ちください。

━━━━━━━━━━━━━━━━━━━━
【お問い合わせ内容】

{$message}
━━━━━━━━━━━━━━━━━━━━

{$site_name}
〒525-0022 滋賀県草津市川原町298-1 2F
TEL：077-569-5697
URL：{$site_url}
EOT;
$auto_headers = [
    'Content-Type: text/plain; charset=UTF-8',
    "From: {$site_name} <{$admin_email}>",
];

// 管理者へのメール送信（失敗したらエラーページへ）
$sent = wp_mail($admin_email, $admin_subject, $admin_body, $admin_headers);
if (!$sent) {
    wp_redirect(home_url('/contact.html?error=2'));
    exit;
}

// 自動返信（失敗しても完了扱い）
wp_mail($email, $auto_subject, $auto_body, $auto_headers);

wp_redirect(home_url('/contact-thanks.html'));
exit;

<?php
require_once CORE_PATH.'/LanguageManager.php';
$settings=$settings??[];
$footerThemeRaw=trim((string)($settings['theme_ic-mimarlik-dekorasyon_settings']??''));
$footerTheme=$footerThemeRaw!==''?json_decode($footerThemeRaw,true):[];
if(!is_array($footerTheme))$footerTheme=[];
$footerTheme=LanguageManager::themeSettings('ic-mimarlik-dekorasyon',LanguageManager::current(),$footerTheme);
$footerSection=$footerTheme['sections']['footer']??[];
$footerKicker=trim((string)($footerSection['kicker']??''))?:'İÇ MİMARLIK · 7/24';
$footerTitle=trim((string)($footerSection['title']??''))?:'Güvenilir iç mimarlık hizmeti, doğru müdahaleyle başlar.';
$footerUseCommon = !array_key_exists('use_common_settings', $footerSection) || !empty($footerSection['use_common_settings']);
$footerCommonSettings = $settings;
if (class_exists('Setting')) {
    $footerAllSettings = Setting::all();
    $footerCommonSettings = LanguageManager::settings(LanguageManager::current(), is_array($footerAllSettings) ? $footerAllSettings : $settings);
}
$footerSource = $footerUseCommon ? $footerCommonSettings : $footerSection;
$footerPhone = trim((string)($footerSource['contact_phone'] ?? ''));
$footerEmail = trim((string)($footerSource['contact_email'] ?? ''));
$footerAddress = trim((string)($footerSource['contact_address'] ?? ''));
$footerInstagram = trim((string)($footerSource['instagram'] ?? ''));
$footerTwitter = trim((string)($footerSource['twitter'] ?? ($footerSource['x'] ?? '')));
$footerYoutube = trim((string)($footerSource['youtube'] ?? ''));
?>
<footer class="ma-footer">
  <div class="ma-footer-brand">
    <span class="ma-kicker"><?=htmlspecialchars($footerKicker,ENT_QUOTES,'UTF-8')?></span>
    <h2><?=nl2br(htmlspecialchars($footerTitle,ENT_QUOTES,'UTF-8'))?></h2>
  </div>
  <div class="ma-footer-columns">
    <div class="ma-footer-contact">
      <span class="ma-footer-label">İLETİŞİM</span>
      <?php if($footerPhone!==''): ?><a href="tel:<?=htmlspecialchars(preg_replace('/[^0-9+]/','',$footerPhone),ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($footerPhone,ENT_QUOTES,'UTF-8')?></a><?php endif; ?>
      <?php if($footerEmail!==''): ?><a href="mailto:<?=htmlspecialchars($footerEmail,ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($footerEmail,ENT_QUOTES,'UTF-8')?></a><?php endif; ?>
      <?php if($footerAddress!==''): ?><p><?=htmlspecialchars($footerAddress,ENT_QUOTES,'UTF-8')?></p><?php endif; ?>
      <?php if($footerInstagram!=='' || $footerTwitter!=='' || $footerYoutube!==''): ?><div class="ma-footer-socials" aria-label="Sosyal medya">
        <?php if($footerInstagram!==''): ?><a href="<?=htmlspecialchars($footerInstagram,ENT_QUOTES,'UTF-8')?>" target="_blank" rel="noopener noreferrer">Instagram</a><?php endif; ?>
        <?php if($footerTwitter!==''): ?><a href="<?=htmlspecialchars($footerTwitter,ENT_QUOTES,'UTF-8')?>" target="_blank" rel="noopener noreferrer">Twitter</a><?php endif; ?>
        <?php if($footerYoutube!==''): ?><a href="<?=htmlspecialchars($footerYoutube,ENT_QUOTES,'UTF-8')?>" target="_blank" rel="noopener noreferrer">YouTube</a><?php endif; ?>
      </div><?php endif; ?>
    </div>
    <div class="ma-footer-sitemap">
      <span class="ma-footer-label">SİTE HARİTASI</span>
      <nav aria-label="Site haritası">
      <?php
      $footerMenus=$menus??[];
      $footerSeen=[];
      foreach($footerMenus as $fm):
        if((int)($fm['parent_id']??0)!==0) continue;
        $ft=trim((string)($fm['title']??''));
        $fk=mb_strtolower(preg_replace('/\s+/u',' ',$ft));
        if(isset($footerSeen[$fk])) continue;
        $footerSeen[$fk]=true;
        $fu=($fk==='anasayfa'||$fk==='ana sayfa')?base_url(''):(string)($fm['url']??'#');
      ?>
        <a href="<?=htmlspecialchars($fu,ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($ft,ENT_QUOTES,'UTF-8')?></a>
      <?php endforeach; ?>
      </nav>
    </div>
  </div>
</footer>
<a class="ma-back-top" href="#ma-top" aria-label="Sayfanın başına dön"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 14 12 8l6 6"></path></svg></a>
<?php $footerCopyrightYear = date('Y'); ?>
<div class="ma-copy ma-copy-protected" data-protected="true" data-protection="theme-permission">© <?=htmlspecialchars($footerCopyrightYear,ENT_QUOTES,'UTF-8')?> <a href="https://tasarimsk.com" target="_blank" rel="noopener noreferrer">Tasarım SK</a></div><script src="<?=base_url('themes/ic-mimarlik-dekorasyon/assets/js/theme.js')?>?v=2.76.88"></script></body></html>
<?php
// Apply AI translations only to visible text nodes. URLs, CSS, scripts, image paths and HTML structure remain unchanged.
if (isset($maTranslationBufferLevel) && ob_get_level() > $maTranslationBufferLevel) {
    $maRenderedOutput = (string)ob_get_clean();
    $maLanguage = LanguageManager::current();
    if ($maLanguage !== 'tr') {
        $maMapRaw = trim((string)Setting::get('theme_ic-mimarlik-dekorasyon_static_text_translations_' . $maLanguage, ''));
        $maMap = $maMapRaw !== '' ? json_decode($maMapRaw, true) : [];
        if (is_array($maMap) && $maMap) {
            $maRenderedOutput = preg_replace_callback('~>([^<>]+)<~s', static function ($match) use ($maMap) {
                $rawText = (string)$match[1];
                $decodedText = trim(html_entity_decode($rawText, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                if ($decodedText === '' || !array_key_exists($decodedText, $maMap)) return $match[0];
                $translatedText = (string)$maMap[$decodedText];
                return '>' . htmlspecialchars($translatedText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '<';
            }, $maRenderedOutput) ?? $maRenderedOutput;
        }
    }
    echo $maRenderedOutput;
}
?>

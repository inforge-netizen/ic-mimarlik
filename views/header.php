<?php
$maTranslationBufferLevel = ob_get_level();
ob_start();
$settings=$settings??[];
require_once CORE_PATH.'/LanguageManager.php';
$asansorPayloadRaw=trim((string)($settings['theme_ic-mimarlik-dekorasyon_settings']??''));
$asansorPayload=$asansorPayloadRaw!==''?json_decode($asansorPayloadRaw,true):[];
if(!is_array($asansorPayload))$asansorPayload=[];
$asansorGeneral=is_array($asansorPayload['general']??null)?$asansorPayload['general']:[];
$asansorUseCommon=!empty($asansorGeneral['use_common_settings']);
// v2.76.27: resolve shared values directly from the common Settings store when enabled.
// This theme was missing from FrontendController's shared-settings merge allowlist; keep
// this local resolution as a safe fallback and never source identity/colors from Marangoz.
$asansorCommonSettings=$settings;
if(class_exists('Setting')){
  $asansorAllSettings=Setting::all();
  if(is_array($asansorAllSettings))$asansorCommonSettings=LanguageManager::settings(LanguageManager::current(),$asansorAllSettings);
}
$asansorIdentitySettings=$asansorUseCommon
  ? $asansorCommonSettings
  : array_replace($settings,$asansorGeneral);
$siteName=trim((string)($asansorIdentitySettings['site_name']??''))?:'İç Mimarlık Stüdyosu';
$siteLogo=trim((string)($asansorIdentitySettings['site_logo']??''));
$menus=$menus??[];
// Menüleri tema ile sınırlama: admin panelinden eklenen yeni menüler ve alt menüler
// canlı sitede de görünmelidir. Aksi halde tema ilk kurulumdaki menüleri gösterir.
$allMenus = $menus;
$topMenus=array_values(array_filter($allMenus,fn($m)=>(int)($m['parent_id']??0)===0));
$childrenByParent=[];
foreach($allMenus as $m){$pid=(int)($m['parent_id']??0);if($pid>0)$childrenByParent[$pid][]=$m;}
$seen=[];$renderMenus=[];
foreach($topMenus as $m){
  $label=trim((string)($m['title']??''));
  $key=mb_strtolower(preg_replace('/\s+/u',' ',$label));
  if($key==='anasayfa'||$key==='ana sayfa'){
    if(isset($seen['anasayfa']))continue;
    $m['url']=base_url('');$seen['anasayfa']=true;
  }
  $url=(string)($m['url']??'#');$ukey=$key.'|'.$url;
  if(isset($seen[$ukey]))continue;
  $seen[$ukey]=true;$renderMenus[]=$m;
}

$languageUrl=static function(string $code):string{
  $url=current_url();
  $url=preg_replace('/([?&])lang=[^&]*/i','$1',$url);
  $url=preg_replace('/[?&]$/','',$url);
  return $url.(str_contains($url,'?')?'&':'?').'lang='.rawurlencode($code);
};
$enabledLanguages=LanguageManager::enabled();
$primary=trim((string)($asansorGeneral['primary_color']??($settings['theme_ic-mimarlik-dekorasyon_primary_color']??'')));
if(!preg_match('/^#[0-9a-fA-F]{6}$/',$primary))$primary='#b68b63';
$secondary=trim((string)($asansorGeneral['secondary_color']??($settings['theme_ic-mimarlik-dekorasyon_secondary_color']??'')));
if(!preg_match('/^#[0-9a-fA-F]{6}$/',$secondary))$secondary='#29251f';
$headerPhone=trim((string)($asansorIdentitySettings['contact_phone']??''));
$headerEmail=trim((string)($asansorIdentitySettings['contact_email']??''));
$headerPhoneDial=preg_replace('/[^0-9+]/','',$headerPhone);
$headerPhoneHref=$headerPhoneDial!==''?'tel:'.$headerPhoneDial:'#iletisim-formu';
$description=trim((string)($metaDescription??$asansorIdentitySettings['seo_description']??''));
$canonicalUrl=$canonicalUrl??current_url();$title=trim((string)($siteTitle??''));if($title==='')$title=trim((string)($asansorIdentitySettings['seo_title']??''));if($title==='')$title=$siteName;
?>
<!doctype html><html lang="<?=htmlspecialchars(LanguageManager::current(),ENT_QUOTES,'UTF-8')?>"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=htmlspecialchars($title)?></title><?php if($description):?><meta name="description" content="<?=htmlspecialchars($description)?>"><?php endif;?><meta name="robots" content="<?=htmlspecialchars($robots??'index, follow')?>"><link rel="canonical" href="<?=htmlspecialchars($canonicalUrl)?>"><meta property="og:title" content="<?=htmlspecialchars($title)?>"><meta property="og:description" content="<?=htmlspecialchars($description)?>"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@400;500&family=Manrope:wght@400;500;600;700&display=swap"><link rel="stylesheet" href="<?=base_url('themes/ic-mimarlik-dekorasyon/assets/css/theme.css')?>?v=2.76.93"><style>:root{--gold:<?=$primary?>;--ink:<?=$secondary?>}html{background:var(--ink)}body{opacity:1}</style></head><body>
<header class="ma-header">
<a class="ma-header-phone" href="<?=htmlspecialchars($headerPhoneHref,ENT_QUOTES,'UTF-8')?>" aria-label="Telefon: <?=htmlspecialchars($headerPhone,ENT_QUOTES,'UTF-8')?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h3l2 5-2 1.5a15 15 0 0 0 5.5 5.5L15 14l5 2v3c0 1-1 2-2 2C10 21 3 14 3 6c0-1 1-2 2-2Z"/></svg><span><?=htmlspecialchars($headerPhone,ENT_QUOTES,'UTF-8')?></span></a><?php if($headerEmail!==''): ?><a class="ma-header-email" href="mailto:<?=htmlspecialchars($headerEmail,ENT_QUOTES,'UTF-8')?>" aria-label="E-posta: <?=htmlspecialchars($headerEmail,ENT_QUOTES,'UTF-8')?>"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg><span><?=htmlspecialchars($headerEmail,ENT_QUOTES,'UTF-8')?></span></a><?php endif; ?>
<a class="ma-logo ma-editorial-logo" href="<?=base_url('')?>" aria-label="İç Mimarlık ve Dekorasyon ana sayfa">
  <img class="ma-coolhunter-mark" src="<?=base_url('themes/ic-mimarlik-dekorasyon/assets/images/navbar-bg.svg')?>" alt="" aria-hidden="true">
  <span class="ma-logo-subtitle"><span class="ma-brand-primary">İÇ MİMARLIK</span><span class="ma-brand-secondary">DEKORASYON</span></span>
</a>
<button class="ma-toggle" type="button" aria-label="Menüyü aç" aria-expanded="false">☰</button>
<nav class="ma-nav" aria-label="Ana menü">
<?php
$renderMenuTree = function(array $items, int $level = 0) use (&$renderMenuTree, $childrenByParent): void {
    foreach($items as $m):
        $label=trim((string)($m['title']??''));
        $key=mb_strtolower($label);
        $url=($key==='anasayfa'||$key==='ana sayfa')?base_url(''):(string)($m['url']??'#');
        $id=(int)($m['id']??0);
        $children=$childrenByParent[$id]??[];
        ?>
        <div class="ma-menu-item<?=count($children)?' has-children':''?><?= $level>0?' ma-menu-subitem':'' ?>">
          <a class="ma-menu-link" href="<?=htmlspecialchars($url,ENT_QUOTES,'UTF-8')?>" target="<?=htmlspecialchars((string)($m['target']??'_self'),ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($label,ENT_QUOTES,'UTF-8')?></a><?php if($children): ?><button class="ma-submenu-toggle" type="button" aria-label="Alt menüyü aç/kapat" aria-expanded="false"><span></span></button><?php endif; ?>
          <?php if($children): ?><div class="ma-submenu"><?php $renderMenuTree($children,$level+1); ?></div><?php endif; ?>
        </div>
        <?php
    endforeach;
};
$renderMenuTree($renderMenus);
?>
</nav>
<?php
$maSocialLinks = [
  'instagram' => trim((string)($asansorIdentitySettings['instagram'] ?? '')),
  'twitter' => trim((string)($asansorIdentitySettings['twitter'] ?? ($asansorIdentitySettings['x'] ?? ''))),
  'youtube' => trim((string)($asansorIdentitySettings['youtube'] ?? '')),
];
$maSocialLinks = array_filter($maSocialLinks, static fn($url) => $url !== '' && $url !== '#');
?>
<div class="ma-header-right-tools">
<div class="ma-socials ma-header-socials" aria-label="Sosyal medya">
<?php foreach($maSocialLinks as $network=>$url): ?>
  <a href="<?=htmlspecialchars($url,ENT_QUOTES,'UTF-8')?>" target="_blank" rel="noopener noreferrer" aria-label="<?=htmlspecialchars(ucfirst($network),ENT_QUOTES,'UTF-8')?>">
  <?php if($network==='instagram'): ?><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="4"/><circle cx="12" cy="12" r="4"/><circle cx="17.6" cy="6.7" r=".8" fill="currentColor" stroke="none"/></svg>
  <?php elseif($network==='twitter'): ?><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 5.9a8 8 0 0 1-2.4.7 4.2 4.2 0 0 0 1.8-2.3 8.4 8.4 0 0 1-2.7 1 4.2 4.2 0 0 0-7.2 3.8A12 12 0 0 1 2.8 4.8a4.2 4.2 0 0 0 1.3 5.6 4.1 4.1 0 0 1-1.9-.5v.1a4.2 4.2 0 0 0 3.4 4.1 4.3 4.3 0 0 1-1.9.1 4.2 4.2 0 0 0 3.9 2.9A8.5 8.5 0 0 1 1 19a12 12 0 0 0 18.5-10.1v-.5A8.6 8.6 0 0 0 22 5.9Z"/></svg>
  <?php elseif($network==='youtube'): ?><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12a31 31 0 0 0 .5 4.8 3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8Z"/><path d="m9.5 15.5 6-3.5-6-3.5Z" fill="currentColor" stroke="none"/></svg><?php endif; ?>
  </a>
<?php endforeach; ?>
<?php if($headerPhoneDial!==''): ?><a class="ma-header-whatsapp" href="https://wa.me/<?=htmlspecialchars(ltrim($headerPhoneDial,'+'),ENT_QUOTES,'UTF-8')?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.2 11.8a8.2 8.2 0 0 1-12.1 7.1L3 20l1.2-4.9a8.2 8.2 0 1 1 16-3.3Z"/><path d="M8.6 8.2c.3-.6.6-.6.9-.6h.4c.2 0 .4.1.5.4l.7 1.7c.1.2.1.4-.1.6l-.5.6c-.2.2-.2.4 0 .6.5.9 1.2 1.6 2.1 2.1.2.1.4.1.6-.1l.7-.8c.2-.2.4-.2.6-.1l1.6.8c.2.1.3.3.3.5 0 .4-.2 1-.6 1.3-.4.4-1.1.7-1.8.6-1-.1-2.3-.7-3.5-1.8-1.4-1.3-2.2-2.9-2.3-3.9-.1-.7.1-1.4.5-1.9Z"/></svg></a><?php endif; ?>
</div>
<details class="ma-header-language">
<summary aria-label="Dil seçimi"><span><?=htmlspecialchars(strtoupper(LanguageManager::current()),ENT_QUOTES,'UTF-8')?></span><svg viewBox="0 0 12 12" aria-hidden="true"><path d="m2 4 4 4 4-4"/></svg></summary>
<div class="ma-language-menu" role="menu"><?php foreach($enabledLanguages as $langCode): $langCode=strtolower((string)$langCode); ?><a role="menuitem" href="<?=htmlspecialchars($languageUrl($langCode),ENT_QUOTES,'UTF-8')?>" class="<?=LanguageManager::current()===$langCode?'is-active':''?>" lang="<?=htmlspecialchars($langCode,ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars(strtoupper($langCode),ENT_QUOTES,'UTF-8')?></a><?php endforeach; ?></div>
</details>
</div>
</header>

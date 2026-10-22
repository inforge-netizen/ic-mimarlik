<?php require __DIR__ . '/header.php'; ?>
<main class="ma-separated-page-content">
<?php
// The section's full designed HTML is stored in the Page record, so editors can modify it in Admin > Pages.
$maSavedContent = (string)($page['content'] ?? '');
$maSavedContent = preg_replace('/^\s*<!--\s*SK_INTERIOR_SECTION_PAGE:[a-z_]+\s*-->\s*/', '', $maSavedContent) ?? $maSavedContent;
if (trim($maSavedContent) !== '') {
    echo $maSavedContent;
} else {
    echo '<section class="ma-intro"><div><span class="ma-kicker">İÇ MİMARLIK HİZMETLERİ</span><h2>' . htmlspecialchars((string)($page['title'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</h2></div></section>';
}
?>
</main>
<?php require __DIR__ . '/footer.php'; ?>

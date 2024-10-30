<?php

/*
Plugin Name: Livechatoo
Plugin URI: https://www.livechatoo.com/
Description: Wordpress plugin to insert Livechatoo JavaScript code to your website
Version: 1.1.3
Author: Livechatoo
Author URI: https://www.livechatoo.com/
License: GPLv2 or later
*/

// define function to show plugin form in admin
function livechatoo_options_menu() { ?>
    <style>
    .form-table tr td { padding: 0; }
    </style>
    <div class="wrap">
        <h1>Livechatoo</h1>
        <div id="existingform">
            <div class="metabox-holder">
                <div class="postbox">
                    <h3 class="hndle"><span><?php echo livechatoo_message(0); ?></span></h3>
                    <div style="padding:10px;">
                        <div style="padding: 10px 0"><?php echo livechatoo_message(2); ?></div>
                        <form method="post" action="options.php">
                            <?php wp_nonce_field('update-options'); ?>
                            <table class="form-table">
                                <tr><td><textarea name="livechatoo" rows="12" style="width: 100%" placeholder="<?php echo livechatoo_message(1) ?>"><?php echo get_option('livechatoo'); ?></textarea></td></tr>
                                <tr><td><input type="submit" class="button-primary" value="<?php echo livechatoo_message(3); ?>"></td></tr>
                            </table>
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="page_options" value="livechatoo">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

// define function to get message depending on language
function livechatoo_message($num) {
    $lang = explode('_', get_locale());
    $lang = $lang[0];

    $msgs = array(
      'cs' => array(
        'Zadejte váš Livechatoo JavaScript kód',
        '<!-- Sem vložte váš Livechatoo JavaScript kód -->',
        'Pevně určený jazyk v JavaScript kódu můžete nahradit za proměnnou {auto} pro automatické použití jazyka vaší stránky v Livechatoo (například změňte lang: \'cs\' na lang: \'{auto}\').',
        'Uložit změny',
      ),
      'de' => array(
        'Geben Sie Ihren Livechatoo-JavaScript-Code ein',
        '<!-- Fügen Sie Ihren Livechatoo-JavaScript-Code hier ein -->',
        'Sie können eine feste Sprache im JavaScript-Code durch die Variable {auto} ersetzen, um Ihre Sprache in Livechatoo automatisch zu verwenden (ändern Sie beispielsweise lang: \'de\' in lang: \'{auto}\').',
        'Änderungen speichern',
      ),
      'en' => array(
        'Enter your Livechatoo JavaScript code',
        '<!-- Paste here your Livechatoo JavaScript code -->',
        'You can replace permanent language code to {auto} variable for automatic use of your site language in Livechatoo (for example change lang: \'en\' to lang: \'{auto}\').',
        'Save Changes',
      ),
      'hu' => array(
        'Adja meg Livechatoo JavaScript kódját',
        '<!-- Itt helyezze el a Livechatoo JavaScript kódját -->',
        'A JavaScript-kódban rögzített nyelvet a {auto} változóval helyettesítheti a nyelv automatikus használatához a Livechatoo-ban (például a lang lang: \'hu\' a lang: \'{auto}\').',
        'Módosítások mentése',
      ),
      'sk' => array(
        'Zadajte váš Livechatoo JavaScript kód',
        '<!-- Sem vložte váš Livechatoo JavaScript kód -->',
        'Pevne určený jazyk v JavaScript kóde môžete nahradiť za premennú {auto} pre automatické použitie jazyka vašej stránky v Livechatoo (napríklad zmeňte lang : \'sk\' na lang : \'{auto}\').',
        'Uložiť zmeny',
      ),
    );

    $lang = (isset($msgs[$lang])) ? $lang : 'en';

    $msg = (isset($msgs[$lang][$num])) ? $msgs[$lang][$num] : '';

    return $msg;
}

// define function to add link to the main admin menu
function livechatoo_menu() {
    add_menu_page(livechatoo_message(0), 'Livechatoo', 'activate_plugins', 'livechatoo', 'livechatoo_options_menu', 'dashicons-format-chat');
}

// define function to output livechatoo js code to the website head
function livechatoo_head() {
    $jscode = get_option('livechatoo');

    if (!empty($jscode)) {
        // set Livechatoo language in JS code automatically by website language
        $locale = explode('_', get_locale());
        $lang = $locale[0];
        $jscode = str_replace('{auto}', $lang, $jscode);

        // append Livechatoo JS code to document
        echo "\n" . $jscode . "\n";
    }
}

// add some initial js code for our option (note: add_option() will not update existing options)
add_option('livechatoo', livechatoo_message(1));

// call function to output livechatoo js code to the website head
add_filter('wp_head', 'livechatoo_head');

// call function to add link to the main admin menu
add_action('admin_menu', 'livechatoo_menu');

?>
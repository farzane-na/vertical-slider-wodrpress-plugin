<?php
function farzaneh_custom_dashboard_widget() {

    wp_add_dashboard_widget(
        'farzaneh_welcome_widget',
        'خوش آمدید❤️☕',
        'farzaneh_welcome_widget_content'
    );
}
add_action('wp_dashboard_setup', 'farzaneh_custom_dashboard_widget');

function farzaneh_welcome_widget_content() {
    ?>
    <div style="width:100%">
        <img style="width:100% !important;"  src="<?php echo WELCOME_IMAGE_FARZANE ?>" alt="Welcome"  />
    </div>
    <?php
}

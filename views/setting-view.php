<div id="setting" class="wrap">
    <h1>Satuin</h1>
    <hr />
    <h2>Settings</h2>
    <form method="post" action="options.php">
        <?php settings_fields('satuin-settings'); ?>
        <?php do_settings_sections('satuin-settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Key</th>
                <td>
                    <input type="text" name="satuin_key" value="<?php echo esc_attr(get_option('satuin_key')); ?>" />
                    <p class="description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, debitis.
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
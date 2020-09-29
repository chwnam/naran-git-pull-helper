<?php
/**
 * Context:
 *
 * @var string $meta_key_webhook_provider
 * @var string $meta_key_local_path
 * @var string $meta_key_secret_token
 * @var string $meta_key_remote_url
 *
 * @var string $value_webhook_provider
 * @var string $value_local_path
 * @var string $value_secret_token
 * @var string $value_remote_url
 * @var string $value_webhook_url
 *
 * @var array<string, string> $available_webhook_providers
 */

?>
<table id="edit-cpt-repository" class="form-table">
    <tr>
        <th scope="row">
            Webhook Provider
        </th>
        <td>
            <ul style="margin: 0">
				<?php foreach ( $available_webhook_providers as $value => $label ) : ?>
                    <li>
                        <input
                                id="<?php echo esc_attr( $meta_key_webhook_provider ); ?>-<?php echo esc_attr( $value ); ?>"
                                name="<?php echo esc_attr( $meta_key_webhook_provider ); ?>"
                                value="<?php echo esc_attr( $value ); ?>"
                                type="radio"
                                required="required"
							<?php checked( $value === $value_webhook_provider ); ?>
                        >
                        <label
                                for="<?php echo esc_attr( $meta_key_webhook_provider ); ?>-<?php echo esc_attr( $value ); ?>"
                        >
							<?php echo esc_html( $label ); ?>
                        </label>
                    </li>
				<?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="<?php echo esc_attr( $meta_key_local_path ); ?>">
                Local Path
            </label>
        </th>
        <td>
            <input
                    id="<?php echo esc_attr( $meta_key_local_path ); ?>"
                    name="<?php echo esc_attr( $meta_key_local_path ); ?>"
                    type="text"
                    class="text large-text"
                    value="<?php echo esc_attr( $value_local_path ); ?>"
                    required="required"
            >
            <p class="description">Relative path to <strong>wp-content</strong>.</p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="<?php echo esc_attr( $meta_key_secret_token ); ?>">
                Secret Token
            </label>
        </th>
        <td>
            <input
                    id="<?php echo esc_attr( $meta_key_secret_token ); ?>"
                    name="<?php echo esc_attr( $meta_key_secret_token ); ?>"
                    type="text"
                    class="text large-text"
                    value="<?php echo esc_attr( $value_secret_token ); ?>"
            >
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="<?php echo esc_attr( $meta_key_remote_url ); ?>">
                Remote URL
            </label>
        </th>
        <td>
            <input
                    id="<?php echo esc_attr( $meta_key_remote_url ); ?>"
                    name="<?php echo esc_attr( $meta_key_remote_url ); ?>"
                    type="text"
                    class="text large-text"
                    value="<?php echo esc_attr( $value_remote_url ); ?>"
            >
        </td>
    </tr>
    <tr>
        <th>
            <label for="webhook-url">Webhook URL</label>
        </th>
        <td>
            <input
                    id="webhook-url"
                    type="text"
                    class="text large-text"
                    readonly="readonly"
                    value="<?php echo esc_url( $value_webhook_url ); ?>"
            >
        </td>
    </tr>
</table>

<?php wp_nonce_field( 'edit-cpt-repository', 'nrgph_nonce', false ); ?>

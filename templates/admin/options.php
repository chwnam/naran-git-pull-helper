<?php
/**
 * @var string $option_group
 * @var string $page
 */
?>

<div class="wrap">
	<?php settings_errors( 'nrgph_settings' ); ?>

    <hr class="wp-header-end">

    <form action="<?php echo admin_url( 'options.php' ); ?>" method="post">

		<?php
		settings_fields( $option_group );

		do_settings_sections( $page );

		submit_button();
		?>

    </form>
</div>

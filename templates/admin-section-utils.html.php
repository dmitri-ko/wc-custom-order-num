<?php
	$tabs = $page->get_tabs();

	$gotenberg_url = $page->get_options()->get(
		'gotenberg_url',
		$tabs['advanced']['fields']['gotenberg_url']
	);

	$gotenberg_default_user = $page->get_options()->get(
		'gotenberg_default_user',
		$tabs['advanced']['fields']['gotenberg_default_user']
	);

	$gotenberg_http_auth_user = $page->get_options()->get(
		'gotenberg_http_auth_user',
		$tabs['advanced']['fields']['gotenberg_http_auth_user']
	);

	?>
<p>
	<?php esc_html_e( 'The DOG DNA advanced settings page', 'wc-custom-order-num' ); ?>
</p>
<p>
	<?php esc_html_e( 'Click Submit button to save changes.', 'wc-custom-order-num' ); ?>
</p>
<div class="ddna-settings">
	<fieldset class="dna-input-box">
		<label class="dna-input-box-description" for="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_url' ) ); ?>"> <?php esc_html_e( 'Gotenberg URL', 'wc-custom-order-num' ); ?></label>
		<input class="dna-input-box-input" type="text" name="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_url' ) ); ?>" id="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_url' ) ); ?>" value="<?php echo esc_attr( $gotenberg_url ); ?>">
	</fieldset>
	<fieldset class="dna-input-box">
		<label class="dna-input-box-description" for="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_default_user' ) ); ?>"> <?php esc_html_e( 'Gotenberg default WordPress user', 'wc-custom-order-num' ); ?></label>
		<input class="dna-input-box-input" type="text" name="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_default_user' ) ); ?>" id="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_default_user' ) ); ?>" value="<?php echo esc_attr( $gotenberg_default_user ); ?>">
	</fieldset>
	<fieldset class="dna-input-box">
		<label class="dna-input-box-description" for="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_http_auth_user' ) ); ?>"> <?php esc_html_e( 'Gotenberg HTTP authentication user', 'wc-custom-order-num' ); ?></label>
		<input class="dna-input-box-input" type="text" name="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_http_auth_user' ) ); ?>" id="<?php echo esc_attr( $page->get_options()->get_option_name( 'gotenberg_http_auth_user' ) ); ?>" value="<?php echo esc_attr( $gotenberg_http_auth_user ); ?>">
	</fieldset>
</div>

<?php
	$tabs = $page->get_tabs();

	$start_date = $page->get_options()->get(
		'start_date',
		$tabs['common']['fields']['start_date']
	);

	$prefix = $page->get_options()->get(
		'prefix',
		$tabs['common']['fields']['prefix']
	);

	$start_num = $page->get_options()->get(
		'start_num',
		$tabs['common']['fields']['start_num']
	);

	$postfix = $page->get_options()->get(
		'postfix',
		$tabs['common']['fields']['postfix']
	);

	?>
<p>
	<?php esc_html_e( 'The Woocommerce custom number main options settings page', 'wc-custom-order-num' ); ?>
</p>
<p>
	<?php esc_html_e( 'Click Submit button to save changes.', 'wc-custom-order-num' ); ?>
</p>
<div class="con-settings">
	<div class="con-settings-group">
		<fieldset class="con-input-box">
			<label class="con-input-box-description" for="<?php echo esc_attr( $page->get_options()->get_option_name( 'start_date' ) ); ?>"> <?php esc_html_e( 'Start date', 'wc-custom-order-num' ); ?></label>
			<input class="con-input-box-input" type="date" name="<?php echo esc_attr( $page->get_options()->get_option_name( 'start_date' ) ); ?>" id="<?php echo esc_attr( $page->get_options()->get_option_name( 'start_date' ) ); ?>" value="<?php echo esc_attr( $start_date ); ?>">
		</fieldset>
		<fieldset class="con-input-box">
			<label class="con-input-box-description" for="<?php echo esc_attr( $page->get_options()->get_option_name( 'start_num' ) ); ?>"> <?php esc_html_e( 'Start number', 'wc-custom-order-num' ); ?></label>
			<input class="con-input-box-input" type="text" name="<?php echo esc_attr( $page->get_options()->get_option_name( 'start_num' ) ); ?>" id="<?php echo esc_attr( $page->get_options()->get_option_name( 'start_num' ) ); ?>" value="<?php echo esc_attr( $start_num ); ?>">
		</fieldset>
		<fieldset class="con-input-box">
			<label class="con-input-box-description" for="<?php echo esc_attr( $page->get_options()->get_option_name( 'prefix' ) ); ?>"> <?php esc_html_e( 'Number prefix', 'wc-custom-order-num' ); ?></label>
			<input class="con-input-box-input" type="text" name="<?php echo esc_attr( $page->get_options()->get_option_name( 'prefix' ) ); ?>" id="<?php echo esc_attr( $page->get_options()->get_option_name( 'prefix' ) ); ?>" value="<?php echo esc_attr( $prefix ); ?>">
		</fieldset>
		<fieldset class="con-input-box">
			<label class="con-input-box-description" for="<?php echo esc_attr( $page->get_options()->get_option_name( 'postfix' ) ); ?>"> <?php esc_html_e( 'Number postfix', 'wc-custom-order-num' ); ?></label>
			<input class="con-input-box-input" type="text" name="<?php echo esc_attr( $page->get_options()->get_option_name( 'postfix' ) ); ?>" id="<?php echo esc_attr( $page->get_options()->get_option_name( 'postfix' ) ); ?>" value="<?php echo esc_attr( $postfix ); ?>">
		</fieldset>
	</div>
</div>

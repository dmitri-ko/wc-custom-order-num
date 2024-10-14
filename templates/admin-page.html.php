
<div class="wrap con-settings" id="con-admin">
	<div class="con-settings-header">
		<div class="con-settings-title-section">
			<h1>
			<?php echo $page->get_page_title(); ?>	
			</h1>
		</div>
	</div>
	<hr class="wp-header-end">
	<?php if ( ! empty( $_GET['updated'] ) ) : ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
			<p>
				<strong><?php esc_html_e( 'Settings Saved.', 'wc-custom-order-num' ); ?></strong>
			</p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'wc-custom-order-num' ); ?></span>
			</button>
		</div>
	<?php endif; ?>
	<?php
		$tabs        = $page->get_tabs();
		$current_tab = isset( $_GET['tab'] ) && isset( $tabs[ $_GET['tab'] ] ) ? $_GET['tab'] : array_key_first( $tabs );
	?>
	<div class="con-settings-body hide-if-no-js">
	<form action="<?php echo $page->get_form_url(); ?>" method="POST">
		<nav class="nav-tab-wrapper">
		<?php
		foreach ( $tabs as $tab => $tab_data ) {
				$current = $tab === $current_tab ? ' nav-tab-active' : '';
				$url     = add_query_arg(
					array(
						'page' => $page->get_slug(),
						'tab'  => $tab,
					),
					''
				);

				echo "<a class=\"nav-tab{$current}\" href=\"{$url}\">{$tab_data['title']}</a>";
		}
		?>
		</nav>
		<?php settings_fields( $tabs[ $current_tab ]['slug'] ); ?>
		<?php do_settings_sections( $tabs[ $current_tab ]['slug'] ); ?>
		<?php submit_button( esc_html__( 'Submit', 'wc-custom-order-num' ) ); ?>
	</form>
	</div>
</div>

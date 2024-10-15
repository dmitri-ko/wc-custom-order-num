<form id="form_regenerte_order_nums" method="GET" action="">
	<input type="hidden" id="_wpnonce" name="_wpnonce" value="ceb2673e0a">		
	<input type="hidden" name="page" value="wc-status">
	<input type="hidden" name="tab" value="wc-custom-order-num-status">
	<input type="hidden" name="action" value="regenerte_order_nums">
</form>

<table class="wc_status_table wc_status_table--tools widefat" cellspacing="0">
	<tbody class="tools">
		<tr class="regenerte_order_nums">
			<th>
				<strong class="name"><?php esc_html_e( 'Regenerate order numbers', 'wc-custom-order-num' ); ?></strong>
				<p class="description">	<?php esc_html_e( 'This tool will update order numbers according to the plugin settings.', 'wc-custom-order-num' ); ?>		</p>
			</th>
			<td class="run-tool">					
				<input type="submit" form="form_regenerte_order_nums" class="button button-large js-con-utils-regenerate" value="<?php esc_html_e( 'Regenerate order numbers', 'wc-custom-order-num' ); ?>">
			</td>
		</tr>
		<tr class="reset_order_nums">
			<th>
				<strong class="name"><?php esc_html_e( 'Regenerate order numbers', 'wc-custom-order-num' ); ?></strong>
				<p class="description">	<?php esc_html_e( 'This tool will reset order numbers according to the plugin settings.', 'wc-custom-order-num' ); ?>		</p>
			</th>
			<td class="run-tool">					
				<input type="submit" form="form_reset_order_nums" class="button button-large js-con-utils-reset" value="<?php esc_html_e( 'Reset order numbers', 'wc-custom-order-num' ); ?>">
			</td>
		</tr>
	</tbody>
</table>
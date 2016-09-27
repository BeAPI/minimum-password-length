<div class="wrap">
	<h2><?php _e( 'Minimum Password Length', 'minimum-password-length' ); ?></h2>
	<form method="post">
        <?php echo wp_nonce_field( 'update_minimum_password_length' ); ?>
		<table class="form-table">
			<tr>
				<th><label for="length"><?php _e( 'Password must be at least', 'minimum-password-length' ); ?></label></th>
				<td>
					<input name="length" id="length" type="number" value="<?php echo esc_attr( $current_length ); ?>" />
				</td>
			</tr>
		</table>
		<p>
			<input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'minimum-password-length' ); ?>" />
		</p>
	</form>
</div>

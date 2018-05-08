<?php
/**
 * The code to add Skype field to user registration form
 * @package es_register_form
 */

/**
 * Add new form element
 */
add_action( 'register_form', 'es_register_form' );

function es_register_form() {

    $skype = ( ! empty( $_POST['skype'] ) ) ? sanitize_text_field( $_POST['skype'] ) : '';
        
    ?>
        <p>
            <label for="skype"><?php _e( 'Skype', 'es-register' ) ?><br />
                <input type="text" name="skype" id="skype" class="input" value="<?php echo esc_attr( $skype ); ?>" size="25" /></label>
        </p>
    <?php
}

/**
 * Add validation
 */
add_filter( 'registration_errors', 'es_registration_errors', 10, 3 );

function es_registration_errors( $errors, $sanitized_user_login, $user_email ) {
    
    if ( empty( $_POST['skype'] ) || ! empty( $_POST['skype'] ) && trim( $_POST['skype'] ) == '' ) {
        $errors->add( 'skype_error', sprintf('<strong>%s</strong>: %s', __( 'ERROR', 'es-register' ), __( 'You must include a Skype.', 'es-register' ) ) );
    }

    return $errors;
}

/**
 * Save extra registration user meta
 */
add_action( 'user_register', 'es_user_register' );

function es_user_register( $user_id ) {
    if ( ! empty( $_POST['skype'] ) ) {
        update_user_meta( $user_id, 'skype', sanitize_text_field( $_POST['skype'] ) );
    }
}


/**
 * Auto-login and redirect user after registration
 */
add_action( 'user_register', 'es_user_auto_login' );

function es_user_has_role($user_id, $role) {
    $user = get_userdata( $user_id );
    $roles = $user->roles; 
    return in_array( $role, (array) $user->roles );
}

function es_user_auto_login( $user_id ) {
    if ( es_user_has_role( $user_id, "customer" ) ) {
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );
        wp_redirect( home_url() . "/favorites/" );
        exit;
    }
}

<?php
/**
 * Navigation Menu functions
 *
 * @package WordPress
 * @subpackage Nav_Menus
 * @since 3.0.0
 */

/**
 * Returns a navigation menu object.
 *
 * @since 3.0.0
 *
 * @param int|string|WP_Term $menu Menu ID, slug, name, or object.
 * @return WP_Term|false False if $menu param isn't supplied or term does not exist, menu object if successful.
 */
function wp_get_nav_menu_object( $menu ) {
	$menu_obj = false;

	if ( is_object( $menu ) ) {
		$menu_obj = $menu;
	}

	if ( $menu && ! $menu_obj ) {
		$menu_obj = get_term( $menu, 'nav_menu' );

		if ( ! $menu_obj ) {
			$menu_obj = get_term_by( 'slug', $menu, 'nav_menu' );
		}

		if ( ! $menu_obj ) {
			$menu_obj = get_term_by( 'name', $menu, 'nav_menu' );
		}
	}

	if ( ! $menu_obj || is_wp_error( $menu_obj ) ) {
		$menu_obj = false;
	}

	/**
	 * Filters the nav_menu term retrieved for wp_get_nav_menu_object().
	 *
	 * @since 4.3.0
	 *
	 * @param WP_Term|false      $menu_obj Term from nav_menu taxonomy, or false if nothing had been found.
	 * @param int|string|WP_Term $menu     The menu ID, slug, name, or object passed to wp_get_nav_menu_object().
	 */
	return apply_filters( 'wp_get_nav_menu_object', $menu_obj, $menu );
}

/**
 * Check if the given ID is a navigation menu.
 *
 * Returns true if it is; false otherwise.
 *
 * @since 3.0.0
 *
 * @param int|string|WP_Term $menu Menu ID, slug, name, or object of menu to check.
 * @return bool Whether the menu exists.
 */
function is_nav_menu( $menu ) {
	if ( ! $menu ) {
		return false;
	}

	$menu_obj = wp_get_nav_menu_object( $menu );

	if (
		$menu_obj &&
		! is_wp_error( $menu_obj ) &&
		! empty( $menu_obj->taxonomy ) &&
		'nav_menu' === $menu_obj->taxonomy
	) {
		return true;
	}

	return false;
}

/**
 * Registers navigation menu locations for a theme.
 *
 * @since 3.0.0
 *
 * @global array $_wp_registered_nav_menus
 *
 * @param array $locations Associative array of menu location identifiers (like a slug) and descriptive text.
 */
function register_nav_menus( $locations = array() ) {
	global $_wp_registered_nav_menus;

	add_theme_support( 'menus' );

	foreach ( $locations as $key => $value ) {
		if ( is_int( $key ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'Nav menu locations must be strings.' ), '5.3.0' );
			break;
		}
	}

	$_wp_registered_nav_menus = array_merge( (array) $_wp_registered_nav_menus, $locations );
}

/**
 * Unregisters a navigation menu location for a theme.
 *
 * @since 3.1.0
 *
 * @global array $_wp_registered_nav_menus
 *
 * @param string $location The menu location identifier.
 * @return bool True on success, false on failure.
 */
function unregister_nav_menu( $location ) {
	global $_wp_registered_nav_menus;

	if ( is_array( $_wp_registered_nav_menus ) && isset( $_wp_registered_nav_menus[ $location ] ) ) {
		unset( $_wp_registered_nav_menus[ $location ] );
		if ( empty( $_wp_registered_nav_menus ) ) {
			_remove_theme_support( 'menus' );
		}
		return true;
	}
	return false;
}

/**
 * Registers a navigation menu location for a theme.
 *
 * @since 3.0.0
 *
 * @param string $location    Menu location identifier, like a slug.
 * @param string $description Menu location descriptive text.
 */
function register_nav_menu( $location, $description ) {
	register_nav_menus( array( $location => $description ) );
}
/**
 * Retrieves all registered navigation menu locations in a theme.
 *
 * @since 3.0.0
 *
 * @global array $_wp_registered_nav_menus
 *
 * @return array Registered navigation menu locations. If none are registered, an empty array.
 */
function get_registered_nav_menus() {
	global $_wp_registered_nav_menus;
	if ( isset( $_wp_registered_nav_menus ) ) {
		return $_wp_registered_nav_menus;
	}
	return array();
}

/**
 * Retrieves all registered navigation menu locations and the menus assigned to them.
 *
 * @since 3.0.0
 *
 * @return array Registered navigation menu locations and the menus assigned them.
 *               If none are registered, an empty array.
 */

function get_nav_menu_locations() {
	$locations = get_theme_mod( 'nav_menu_locations' );
	return ( is_array( $locations ) ) ? $locations : array();
}

/**
 * Determines whether a registered nav menu location has a menu assigned to it.
 *
 * @since 3.0.0
 *
 * @param string $location Menu location identifier.
 * @return bool Whether location has a menu.
 */
function has_nav_menu( $location ) {
	$has_nav_menu = false;

	$registered_nav_menus = get_registered_nav_menus();
	if ( isset( $registered_nav_menus[ $location ] ) ) {
		$locations    = get_nav_menu_locations();
		$has_nav_menu = ! empty( $locations[ $location ] );
	}

	/**
	 * Filters whether a nav menu is assigned to the specified location.
	 *
	 * @since 4.3.0
	 *
	 * @param bool   $has_nav_menu Whether there is a menu assigned to a location.
	 * @param string $location     Menu location.
	 */
	return apply_filters( 'has_nav_menu', $has_nav_menu, $location );
}

/**
 * Returns the name of a navigation menu.
 *
 * @since 4.9.0
 *
 * @param string $location Menu location identifier.
 * @return string Menu name.
 */
function wp_get_nav_menu_name( $location ) {
	$menu_name = '';

	$locations = get_nav_menu_locations();

	if ( isset( $locations[ $location ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $location ] );

		if ( $menu && $menu->name ) {
			$menu_name = $menu->name;
		}
	}

	/**
	 * Filters the navigation menu name being returned.
	 *
	 * @since 4.9.0
	 *
	 * @param string $menu_name Menu name.
	 * @param string $location  Menu location identifier.
	 */
	return apply_filters( 'wp_get_nav_menu_name', $menu_name, $location );
}

/**
 * Determines whether the given ID is a nav menu item.
 *
 * @since 3.0.0
 *
 * @param int $menu_item_id The ID of the potential nav menu item.
 * @return bool Whether the given ID is that of a nav menu item.
 */
function is_nav_menu_item( $menu_item_id = 0 ) {
	return ( ! is_wp_error( $menu_item_id ) && ( 'nav_menu_item' === get_post_type( $menu_item_id ) ) );
}

/**
 * Creates a navigation menu.
 *
 * Note that `$menu_name` is expected to be pre-slashed.
 *
 * @since 3.0.0
 *
 * @param string $menu_name Menu name.
 * @return int|WP_Error Menu ID on success, WP_Error object on failure.
 */
function wp_create_nav_menu( $menu_name ) {
	// expected_slashed ($menu_name)
	return wp_update_nav_menu_object( 0, array( 'menu-name' => $menu_name ) );
}

/**
 * Delete a Navigation Menu.
 *
 * @since 3.0.0
 *
 * @param int|string|WP_Term $menu Menu ID, slug, name, or object.
 * @return bool|WP_Error True on success, false or WP_Error object on failure.
 */
function wp_delete_nav_menu( $menu ) {
	$menu = wp_get_nav_menu_object( $menu );
	if ( ! $menu ) {
		return false;
	}

	$menu_objects = get_objects_in_term( $menu->term_id, 'nav_menu' );
	if ( ! empty( $menu_objects ) ) {
		foreach ( $menu_objects as $item ) {
			wp_delete_post( $item );
		}
	}

	$result = wp_delete_term( $menu->term_id, 'nav_menu' );

	// Remove this menu from any locations.
	$locations = get_nav_menu_locations();
	foreach ( $locations as $location => $menu_id ) {
		if ( $menu_id == $menu->term_id ) {
			$locations[ $location ] = 0;
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	if ( $result && ! is_wp_error( $result ) ) {

		/**
		 * Fires after a navigation menu has been successfully deleted.
		 *
		 * @since 3.0.0
		 *
		 * @param int $term_id ID of the deleted menu.
		 */
		do_action( 'wp_delete_nav_menu', $menu->term_id );
	}

	return $result;
}

/**
 * Save the properties of a menu or create a new menu with those properties.
 *
 * Note that `$menu_data` is expected to be pre-slashed.
 *
 * @since 3.0.0
 *
 * @param int   $menu_id   The ID of the menu or "0" to create a new menu.
 * @param array $menu_data The array of menu data.
 * @return int|WP_Error Menu ID on success, WP_Error object on failure.
 */
function wp_update_nav_menu_object( $menu_id = 0, $menu_data = array() ) {
	// expected_slashed ($menu_data)
	$menu_id = (int) $menu_id;

	$_menu = wp_get_nav_menu_object( $menu_id );

	$args = array(
		'description' => ( isset( $menu_data['description'] ) ? $menu_data['description'] : '' ),
		'name'        => ( isset( $menu_data['menu-name'] ) ? $menu_data['menu-name'] : '' ),
		'parent'      => ( isset( $menu_data['parent'] ) ? (int) $menu_data['parent'] : 0 ),
		'slug'        => null,
	);

	// Double-check that we're not going to have one menu take the name of another.
	$_possible_existing = get_term_by( 'name', $menu_data['menu-name'], 'nav_menu' );
	if (
		$_possible_existing &&
		! is_wp_error( $_possible_existing ) &&
		isset( $_possible_existing->term_id ) &&
		$_possible_existing->term_id != $menu_id
	) {
		return new WP_Error(
			'menu_exists',
			sprintf(
				/* translators: %s: Menu name. */
				__( 'The menu name %s conflicts with another menu name. Please try another.' ),
				'<strong>' . esc_html( $menu_data['menu-name'] ) . '</strong>'
			)
		);
	}

	// Menu doesn't already exist, so create a new menu.
	if ( ! $_menu || is_wp_error( $_menu ) ) {
		$menu_exists = get_term_by( 'name', $menu_data['menu-name'], 'nav_menu' );

		if ( $menu_exists ) {
			return new WP_Error(
				'menu_exists',
				sprintf(
					/* translators: %s: Menu name. */
					__( 'The menu name %s conflicts with another menu name. Please try another.' ),
					'<strong>' . esc_html( $menu_data['menu-name'] ) . '</strong>'
				)
			);
		}

		$_menu = wp_insert_term( $menu_data['menu-name'], 'nav_menu', $args );

		if ( is_wp_error( $_menu ) ) {
			return $_menu;
		}

		/**
		 * Fires after a navigation menu is successfully created.
		 *
		 * @since 3.0.0
		 *
		 * @param int   $term_id   ID of the new menu.
		 * @param array $menu_data An array of menu data.
		 */
		do_action( 'wp_create_nav_menu', $_menu['term_id'], $menu_data );

		return (int) $_menu['term_id'];
	}

	if ( ! $_menu || ! isset( $_menu->term_id ) ) {
		return 0;
	}

	$menu_id = (int) $_menu->term_id;

	$update_response = wp_update_term( $menu_id, 'nav_menu', $args );

	if ( is_wp_error( $update_response ) ) {
		return $update_response;
	}

	$menu_id = (int) $update_response['term_id'];

	/**
	 * Fires after a navigation menu has been successfully updated.
	 *
	 * @since 3.0.0
	 *
	 * @param int   $menu_id   ID of the updated menu.
	 * @param array $menu_data An array of menu data.
	 */
	do_action( 'wp_update_nav_menu', $menu_id, $menu_data );
	return $menu_id;
}

/**
 * Save the properties of a menu item or create a new one.
 *
 * The menu-item-title, menu-item-description, and menu-item-attr-title are expected
 * to be pre-slashed since they are passed directly into `wp_insert_post()`.
 *
 * @since 3.0.0
 *
 * @param int   $menu_id         The ID of the menu. Required. If "0", makes the menu item a draft orphan.
 * @param int   $menu_item_db_id The ID of the menu item. If "0", creates a new menu item.
 * @param array $menu_item_data  The menu item's data.
 * @return int|WP_Error The menu item's database ID or WP_Error object on failure.
 */
function wp_update_nav_menu_item( $menu_id = 0, $menu_item_db_id = 0, $menu_item_data = array() ) {
	$menu_id         = (int) $menu_id;
	$menu_item_db_id = (int) $menu_item_db_id;

	// Make sure that we don't convert non-nav_menu_item objects into nav_menu_item objects.
	if ( ! empty( $menu_item_db_id ) && ! is_nav_menu_item( $menu_item_db_id ) ) {
		return new WP_Error( 'update_nav_menu_item_failed', __( 'The given object ID is not that of a menu item.' ) );
	}

	$menu = wp_get_nav_menu_object( $menu_id );

	if ( ! $menu && 0 !== $menu_id ) {
		return new WP_Error( 'invalid_menu_id', __( 'Invalid menu ID.' ) );
	}

	if ( is_wp_error( $menu ) ) {
		return $menu;
	}

	$defaults = array(
		'menu-item-db-id'       => $menu_item_db_id,
		'menu-item-object-id'   => 0,
		'menu-item-object'      => '',
		'menu-item-parent-id'   => 0,
		'menu-item-position'    => 0,
		'menu-item-type'        => 'custom',
		'menu-item-title'       => '',
		'menu-item-url'         => '',
		'menu-item-description' => '',
		'menu-item-attr-title'  => '',
		'menu-item-target'      => '',
		'menu-item-classes'     => '',
		'menu-item-xfn'         => '',
		'menu-item-status'      => '',
	);

	$args = wp_parse_args( $menu_item_data, $defaults );

	if ( 0 == $menu_id ) {
		$args['menu-item-position'] = 1;
	} elseif ( 0 == (int) $args['menu-item-position'] ) {
		$menu_items                 = 0 == $menu_id ? array() : (array) wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'publish,draft' ) );
		$last_item                  = array_pop( $menu_items );
		$args['menu-item-position'] = ( $last_item && isset( $last_item->menu_order ) ) ? 1 + $last_item->menu_order : count( $menu_items );
	}

	$original_parent = 0 < $menu_item_db_id ? get_post_field( 'post_parent', $menu_item_db_id ) : 0;

	if ( 'custom' === $args['menu-item-type'] ) {
		// If custom menu item, trim the URL.
		$args['menu-item-url'] = trim( $args['menu-item-url'] );
	} else {
		/*
		 * If non-custom menu item, then:
		 * - use the original object's URL.
		 * - blank default title to sync with the original object's title.
		 */

		$args['menu-item-url'] = '';

		$original_title = '';
		if ( 'taxonomy' === $args['menu-item-type'] ) {
			$original_parent = get_term_field( 'parent', $args['menu-item-object-id'], $args['menu-item-object'], 'raw' );
			$original_title  = get_term_field( 'name', $args['menu-item-object-id'], $args['menu-item-object'], 'raw' );
		} elseif ( 'post_type' === $args['menu-item-type'] ) {

			$original_object = get_post( $args['menu-item-object-id'] );
			$original_parent = (int) $original_object->post_parent;
			$original_title  = $original_object->post_title;
		} elseif ( 'post_type_archive' === $args['menu-item-type'] ) {
			$original_object = get_post_type_object( $args['menu-item-object'] );
			if ( $original_object ) {
				$original_title = $original_object->labels->archives;
			}
		}

		if ( wp_unslash( $args['menu-item-title'] ) === wp_specialchars_decode( $original_title ) ) {
			$args['menu-item-title'] = '';
		}

		// Hack to get wp to create a post object when too many properties are empty.
		if ( '' === $args['menu-item-title'] && '' === $args['menu-item-description'] ) {
			$args['menu-item-description'] = ' ';
		}
	}

	// Populate the menu item object.
	$post = array(
		'menu_order'   => $args['menu-item-position'],
		'ping_status'  => 0,
		'post_content' => $args['menu-item-description'],
		'post_excerpt' => $args['menu-item-attr-title'],
		'post_parent'  => $original_parent,
		'post_title'   => $args['menu-item-title'],
		'post_type'    => 'nav_menu_item',
	);

	$update = 0 != $menu_item_db_id;

	// New menu item. Default is draft status.
	if ( ! $update ) {
		$post['ID']          = 0;
		$post['post_status'] = 'publish' === $args['menu-item-status'] ? 'publish' : 'draft';
		$menu_item_db_id     = wp_insert_post( $post );
		if ( ! $menu_item_db_id || is_wp_error( $menu_item_db_id ) ) {
			return $menu_item_db_id;
		}

		/**
		 * Fires immediately after a new navigation menu item has been added.
		 *
		 * @since 4.4.0
		 *
		 * @see wp_update_nav_menu_item()
		 *
		 * @param int   $menu_id         ID of the updated menu.
		 * @param int   $menu_item_db_id ID of the new menu item.
		 * @param array $args            An array of arguments used to update/add the menu item.
		 */
		do_action( 'wp_add_nav_menu_item', $menu_id, $menu_item_db_id, $args );
	}

	// Associate the menu item with the menu term.
	// Only set the menu term if it isn't set to avoid unnecessary wp_get_object_terms().
	if ( $menu_id && ( ! $update || ! is_object_in_term( $menu_item_db_id, 'nav_menu', (int) $menu->term_id ) ) ) {
		wp_set_object_terms( $menu_item_db_id, array( $menu->term_id ), 'nav_menu' );
	}

	if ( 'custom' === $args['menu-item-type'] ) {
		$args['menu-item-object-id'] = $menu_item_db_id;
		$args['menu-item-object']    = 'custom';
	}

	$menu_
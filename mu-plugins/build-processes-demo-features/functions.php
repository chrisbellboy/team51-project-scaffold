<?php

defined( 'ABSPATH' ) || exit;

/**
 * Returns the plugin's slug.
 *
 * @since   0.1.0
 * @version 0.1.0
 *
 * @return  string
 */
function bpd_features_get_slug(): string {
	return 'bpd-features';
}

/**
 * Returns an array with meta information for a given asset path. First, it checks for an .asset.php file in the same directory
 * as the given asset file whose contents are returns if it exists. If not, it returns an array with the file's last modified
 * time as the version and the main stylesheet + any extra dependencies passed in as the dependencies.
 *
 * @since   0.1.0
 * @version 0.1.0
 *
 * @param   string      $asset_path             The path to the asset file.
 * @param   array|null  $extra_dependencies     Any extra dependencies to include in the returned meta.
 *
 * @return  array|null
 */
function bpd_features_get_asset_meta( string $asset_path, ?array $extra_dependencies = null ): ?array {
	if ( ! file_exists( $asset_path ) || ! str_starts_with( $asset_path, BPD_FEATURES_DIR ) ) {
		return null;
	}

	$asset_path_info = pathinfo( $asset_path );
	if ( file_exists( $asset_path_info['dirname'] . '/' . $asset_path_info['filename'] . '.asset.php' ) ) {
		$asset_meta = require $asset_path_info['dirname'] . '/' . $asset_path_info['filename'] . '.asset.php';
	} else {
		$asset_meta = array(
			'dependencies' => $extra_dependencies ?? array(),
			'version'      => filemtime( $asset_path ),
		);
		if ( false === $asset_meta['version'] ) { // Safeguard against filemtime() returning false.
			$asset_meta['version'] = BPD_FEATURES_VERSION;
		}
	}

	return $asset_meta;
}

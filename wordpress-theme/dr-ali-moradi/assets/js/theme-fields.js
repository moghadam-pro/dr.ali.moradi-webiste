/**
 * Generic block-editor sidebar panel for the theme's native custom fields.
 * Reads its field list from window.drAliMoradiThemeFields (localized in
 * inc/meta-fields.php) so PHP stays the single source of truth for which
 * fields exist on which post type.
 */
( function ( wp ) {
	if ( ! wp || ! wp.plugins || ! wp.editPost ) {
		return;
	}

	var registerPlugin = wp.plugins.registerPlugin;
	var PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var el = wp.element.createElement;
	var __ = wp.i18n.__;

	var postType = wp.data.select( 'core/editor' ).getCurrentPostType();
	var allFields = window.drAliMoradiThemeFields || {};
	var fields = allFields[ postType ];

	if ( ! fields || ! fields.length ) {
		return;
	}

	function ThemeFieldsPanel() {
		var meta = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
		}, [] );
		var editPost = useDispatch( 'core/editor' ).editPost;

		function setField( key, value ) {
			var next = {};
			Object.keys( meta ).forEach( function ( k ) {
				next[ k ] = meta[ k ];
			} );
			next[ key ] = value;
			editPost( { meta: next } );
		}

		var controls = fields.map( function ( field ) {
			var value = meta[ field.key ] || '';

			if ( 'select' === field.type ) {
				return el( SelectControl, {
					key: field.key,
					label: field.label,
					value: value,
					options: field.options,
					onChange: function ( next ) {
						setField( field.key, next );
					},
				} );
			}

			return el( TextControl, {
				key: field.key,
				label: field.label,
				type: 'number' === field.type ? 'number' : 'url' === field.type ? 'url' : 'text',
				value: value,
				onChange: function ( next ) {
					setField( field.key, next );
				},
			} );
		} );

		return el(
			PluginDocumentSettingPanel,
			{ name: 'dr-ali-moradi-fields', title: __( 'Details', 'dr-ali-moradi' ) },
			controls
		);
	}

	registerPlugin( 'dr-ali-moradi-theme-fields', { render: ThemeFieldsPanel } );
} )( window.wp );

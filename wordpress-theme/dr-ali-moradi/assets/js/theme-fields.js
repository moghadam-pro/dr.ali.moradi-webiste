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
	var Button = wp.components.Button;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var el = wp.element.createElement;
	var __ = wp.i18n.__;

	function LinksControl( props ) {
		var rows = Array.isArray( props.value ) ? props.value : [];

		function updateRow( index, key, next ) {
			var copy = rows.map( function ( row ) {
				return { url: row.url || '', title: row.title || '' };
			} );
			copy[ index ][ key ] = next;
			props.onChange( copy );
		}

		function removeRow( index ) {
			props.onChange( rows.filter( function ( _row, i ) {
				return i !== index;
			} ) );
		}

		function addRow() {
			props.onChange( rows.concat( [ { url: '', title: '' } ] ) );
		}

		return el(
			'div',
			{ className: 'dam-links-control' },
			el( 'label', { className: 'dam-links-control-label' }, props.label ),
			rows.map( function ( row, index ) {
				return el(
					'div',
					{ key: index, className: 'dam-links-control-row' },
					el( TextControl, {
						label: __( 'Title', 'dr-ali-moradi' ),
						value: row.title || '',
						onChange: function ( next ) {
							updateRow( index, 'title', next );
						},
					} ),
					el( TextControl, {
						label: __( 'URL', 'dr-ali-moradi' ),
						type: 'url',
						value: row.url || '',
						onChange: function ( next ) {
							updateRow( index, 'url', next );
						},
					} ),
					el( Button, {
						isDestructive: true,
						variant: 'tertiary',
						size: 'small',
						onClick: function () {
							removeRow( index );
						},
					}, __( 'Remove', 'dr-ali-moradi' ) )
				);
			} ),
			el( Button, {
				variant: 'secondary',
				size: 'small',
				onClick: addRow,
			}, __( '+ Add link', 'dr-ali-moradi' ) )
		);
	}

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

			if ( 'links' === field.type ) {
				return el( LinksControl, {
					key: field.key,
					label: field.label,
					value: meta[ field.key ] || [],
					onChange: function ( next ) {
						setField( field.key, next );
					},
				} );
			}

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

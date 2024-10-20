( function( blocks, element, blockEditor, components, i18n ) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { InspectorControls } = blockEditor;
    const { TextControl, PanelBody } = components;
    const { __ } = i18n;

    registerBlockType( 'reading-time-wp-block/rt-reading-time', {
        title: __( 'Reading Time', 'reading-time-wp-block' ),
        icon: 'clock',
        category: 'widgets',
        attributes: {
            label: {
                type: 'string',
                default: ''
            },
            postfix: {
                type: 'string',
                default: 'min read'
            },
            postfixSingular: {
                type: 'string',
                default: 'min read'
            }
        },
        edit: function( props ) {
            const { attributes, setAttributes } = props;
            const { label, postfix, postfixSingular } = attributes;

            // Construct preview text using a placeholder
            const previewText = `${label ? label + ' ' : ''}# ${postfix}`;

            return [
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: __( 'Reading Time Settings', 'reading-time-wp-block' ), initialOpen: true },
                        el(
                            TextControl,
                            {
                                label: __( 'Reading time label:', 'reading-time-wp-block' ),
                                value: label,
                                onChange: ( newValue ) => setAttributes( { label: newValue } ),
                                placeholder: __( 'Leave blank for none', 'reading-time-wp-block' )
                            }
                        ),
                        el(
                            TextControl,
                            {
                                label: __( 'Reading time postfix:', 'reading-time-wp-block' ),
                                value: postfix,
                                onChange: ( newValue ) => setAttributes( { postfix: newValue } ),
                                placeholder: __( 'min read', 'reading-time-wp-block' )
                            }
                        ),
                        el(
                            TextControl,
                            {
                                label: __( 'Reading time postfix singular:', 'reading-time-wp-block' ),
                                value: postfixSingular,
                                onChange: ( newValue ) => setAttributes( { postfixSingular: newValue } ),
                                placeholder: __( 'min read', 'reading-time-wp-block' )
                            }
                        )
                    )
                ),
                el(
                    'p',
                    { className: 'rt-reading-time' },
                    previewText
                )
            ];
        },
        save: function() {
            return null; // Server-side rendering only
        }
    } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n );
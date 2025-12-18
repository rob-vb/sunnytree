/**
 * Category Grid Block - Editor Script
 *
 * @package SunnyTree
 */

(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var __ = wp.i18n.__;

    var useBlockProps = wp.blockEditor.useBlockProps;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
    var URLInput = wp.blockEditor.URLInput;
    var RichText = wp.blockEditor.RichText;

    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var Button = wp.components.Button;
    var Flex = wp.components.Flex;
    var FlexItem = wp.components.FlexItem;
    var Placeholder = wp.components.Placeholder;

    /**
     * Helper component for media upload
     */
    function MediaUploadControl(props) {
        var label = props.label;
        var mediaId = props.mediaId;
        var mediaUrl = props.mediaUrl;
        var onSelect = props.onSelect;
        var onRemove = props.onRemove;

        return el(
            'div',
            { className: 'components-base-control' },
            el('label', { className: 'components-base-control__label' }, label),
            el(
                MediaUploadCheck,
                null,
                el(MediaUpload, {
                    onSelect: onSelect,
                    allowedTypes: ['image'],
                    value: mediaId,
                    render: function (obj) {
                        return el(
                            'div',
                            { className: 'editor-media-upload' },
                            mediaUrl
                                ? el(
                                      Fragment,
                                      null,
                                      el('img', {
                                          src: mediaUrl,
                                          style: {
                                              maxWidth: '100%',
                                              marginBottom: '10px',
                                              display: 'block',
                                          },
                                      }),
                                      el(
                                          Flex,
                                          null,
                                          el(
                                              FlexItem,
                                              null,
                                              el(
                                                  Button,
                                                  { onClick: obj.open, variant: 'secondary', size: 'small' },
                                                  __('Replace', 'sunnytree')
                                              )
                                          ),
                                          el(
                                              FlexItem,
                                              null,
                                              el(
                                                  Button,
                                                  { onClick: onRemove, variant: 'tertiary', isDestructive: true, size: 'small' },
                                                  __('Remove', 'sunnytree')
                                              )
                                          )
                                      )
                                  )
                                : el(
                                      Button,
                                      { onClick: obj.open, variant: 'secondary' },
                                      __('Select Image', 'sunnytree')
                                  )
                        );
                    },
                })
            )
        );
    }

    /**
     * Create section panel controls
     */
    function createSectionPanel(props, sectionNum, initialOpen) {
        var attributes = props.attributes;
        var setAttributes = props.setAttributes;

        var prefix = 'section' + sectionNum;
        var imageId = attributes[prefix + 'ImageId'];
        var imageUrl = attributes[prefix + 'ImageUrl'];
        var imageAlt = attributes[prefix + 'ImageAlt'];
        var title = attributes[prefix + 'Title'];
        var linkUrl = attributes[prefix + 'LinkUrl'];
        var linkText = attributes[prefix + 'LinkText'];
        var linkTarget = attributes[prefix + 'LinkTarget'];

        var onSelectImage = function (media) {
            var newAttrs = {};
            newAttrs[prefix + 'ImageId'] = media.id;
            newAttrs[prefix + 'ImageUrl'] = media.url;
            newAttrs[prefix + 'ImageAlt'] = media.alt || '';
            setAttributes(newAttrs);
        };

        var onRemoveImage = function () {
            var newAttrs = {};
            newAttrs[prefix + 'ImageId'] = 0;
            newAttrs[prefix + 'ImageUrl'] = '';
            newAttrs[prefix + 'ImageAlt'] = '';
            setAttributes(newAttrs);
        };

        return el(
            PanelBody,
            {
                title: __('Section ', 'sunnytree') + sectionNum,
                initialOpen: initialOpen,
            },

            MediaUploadControl({
                label: __('Image', 'sunnytree'),
                mediaId: imageId,
                mediaUrl: imageUrl,
                onSelect: onSelectImage,
                onRemove: onRemoveImage,
            }),

            imageUrl &&
                el(TextControl, {
                    label: __('Image Alt Text', 'sunnytree'),
                    value: imageAlt,
                    onChange: function (value) {
                        var newAttrs = {};
                        newAttrs[prefix + 'ImageAlt'] = value;
                        setAttributes(newAttrs);
                    },
                }),

            el(TextControl, {
                label: __('Title', 'sunnytree'),
                value: title,
                onChange: function (value) {
                    var newAttrs = {};
                    newAttrs[prefix + 'Title'] = value;
                    setAttributes(newAttrs);
                },
            }),

            el(TextControl, {
                label: __('Link Text', 'sunnytree'),
                value: linkText,
                onChange: function (value) {
                    var newAttrs = {};
                    newAttrs[prefix + 'LinkText'] = value;
                    setAttributes(newAttrs);
                },
            }),

            el(
                'div',
                { className: 'components-base-control' },
                el('label', { className: 'components-base-control__label' }, __('Link URL', 'sunnytree')),
                el(URLInput, {
                    value: linkUrl,
                    onChange: function (value) {
                        var newAttrs = {};
                        newAttrs[prefix + 'LinkUrl'] = value;
                        setAttributes(newAttrs);
                    },
                })
            ),

            el(SelectControl, {
                label: __('Link Target', 'sunnytree'),
                value: linkTarget,
                options: [
                    { label: __('Same Window', 'sunnytree'), value: '_self' },
                    { label: __('New Tab', 'sunnytree'), value: '_blank' },
                ],
                onChange: function (value) {
                    var newAttrs = {};
                    newAttrs[prefix + 'LinkTarget'] = value;
                    setAttributes(newAttrs);
                },
            })
        );
    }

    /**
     * Create section card for editor preview
     */
    function createSectionCard(props, sectionNum) {
        var attributes = props.attributes;
        var setAttributes = props.setAttributes;

        var prefix = 'section' + sectionNum;
        var imageUrl = attributes[prefix + 'ImageUrl'];
        var imageAlt = attributes[prefix + 'ImageAlt'];
        var title = attributes[prefix + 'Title'];
        var linkText = attributes[prefix + 'LinkText'];

        return el(
            'div',
            { className: 'sunnytree-category-grid__item' },
            el(
                'div',
                { className: 'sunnytree-category-grid__image-wrapper' },
                imageUrl
                    ? el('img', {
                          src: imageUrl,
                          alt: imageAlt,
                          className: 'sunnytree-category-grid__image',
                      })
                    : el(Placeholder, {
                          icon: 'format-image',
                          label: __('Section ', 'sunnytree') + sectionNum,
                      })
            ),
            el(
                'div',
                { className: 'sunnytree-category-grid__content' },
                el(RichText, {
                    tagName: 'h3',
                    className: 'sunnytree-category-grid__title',
                    placeholder: __('Title...', 'sunnytree'),
                    value: title,
                    onChange: function (value) {
                        var newAttrs = {};
                        newAttrs[prefix + 'Title'] = value;
                        setAttributes(newAttrs);
                    },
                    allowedFormats: [],
                }),
                linkText &&
                    el(
                        'span',
                        { className: 'sunnytree-category-grid__link' },
                        linkText,
                        el('span', { className: 'sunnytree-category-grid__arrow' }, '\u2192')
                    )
            )
        );
    }

    registerBlockType('sunnytree/category-grid', {
        edit: function (props) {
            var blockProps = useBlockProps({
                className: 'sunnytree-category-grid',
            });

            return el(
                Fragment,
                null,

                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    null,
                    createSectionPanel(props, 1, true),
                    createSectionPanel(props, 2, false),
                    createSectionPanel(props, 3, false),
                    createSectionPanel(props, 4, false),
                    createSectionPanel(props, 5, false),
                    createSectionPanel(props, 6, false)
                ),

                // Editor Preview
                el(
                    'div',
                    blockProps,
                    el(
                        'div',
                        { className: 'sunnytree-category-grid__container' },
                        createSectionCard(props, 1),
                        createSectionCard(props, 2),
                        createSectionCard(props, 3),
                        createSectionCard(props, 4),
                        createSectionCard(props, 5),
                        createSectionCard(props, 6)
                    )
                )
            );
        },

        save: function () {
            return null;
        },
    });
})(window.wp);

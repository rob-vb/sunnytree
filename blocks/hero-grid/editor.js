/**
 * Hero Grid Block - Editor Script
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
    var TextareaControl = wp.components.TextareaControl;
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
            el(
                'label',
                { className: 'components-base-control__label' },
                label
            ),
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
                                                  {
                                                      onClick: obj.open,
                                                      variant: 'secondary',
                                                      size: 'small',
                                                  },
                                                  __('Replace', 'sunnytree')
                                              )
                                          ),
                                          el(
                                              FlexItem,
                                              null,
                                              el(
                                                  Button,
                                                  {
                                                      onClick: onRemove,
                                                      variant: 'tertiary',
                                                      isDestructive: true,
                                                      size: 'small',
                                                  },
                                                  __('Remove', 'sunnytree')
                                              )
                                          )
                                      )
                                  )
                                : el(
                                      Button,
                                      {
                                          onClick: obj.open,
                                          variant: 'secondary',
                                      },
                                      __('Select Image', 'sunnytree')
                                  )
                        );
                    },
                })
            )
        );
    }

    registerBlockType('sunnytree/hero-grid', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var blockProps = useBlockProps({
                className: 'sunnytree-hero-grid',
            });

            // Section 1 handlers
            var onSelectSection1Image = function (media) {
                setAttributes({
                    section1ImageId: media.id,
                    section1ImageUrl: media.url,
                    section1ImageAlt: media.alt || '',
                });
            };

            var onRemoveSection1Image = function () {
                setAttributes({
                    section1ImageId: 0,
                    section1ImageUrl: '',
                    section1ImageAlt: '',
                });
            };

            // Section 2 handlers
            var onSelectSection2Image = function (media) {
                setAttributes({
                    section2ImageId: media.id,
                    section2ImageUrl: media.url,
                    section2ImageAlt: media.alt || '',
                });
            };

            var onRemoveSection2Image = function () {
                setAttributes({
                    section2ImageId: 0,
                    section2ImageUrl: '',
                    section2ImageAlt: '',
                });
            };

            // Section 3 handlers
            var onSelectSection3Image = function (media) {
                setAttributes({
                    section3ImageId: media.id,
                    section3ImageUrl: media.url,
                    section3ImageAlt: media.alt || '',
                });
            };

            var onRemoveSection3Image = function () {
                setAttributes({
                    section3ImageId: 0,
                    section3ImageUrl: '',
                    section3ImageAlt: '',
                });
            };

            return el(
                Fragment,
                null,

                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    null,

                    // Section 1 Panel
                    el(
                        PanelBody,
                        {
                            title: __('Section 1 (Large Left)', 'sunnytree'),
                            initialOpen: true,
                        },

                        MediaUploadControl({
                            label: __('Background Image', 'sunnytree'),
                            mediaId: attributes.section1ImageId,
                            mediaUrl: attributes.section1ImageUrl,
                            onSelect: onSelectSection1Image,
                            onRemove: onRemoveSection1Image,
                        }),

                        attributes.section1ImageUrl &&
                            el(TextControl, {
                                label: __('Image Alt Text', 'sunnytree'),
                                value: attributes.section1ImageAlt,
                                onChange: function (value) {
                                    setAttributes({ section1ImageAlt: value });
                                },
                            }),

                        el(TextControl, {
                            label: __('Title', 'sunnytree'),
                            value: attributes.section1Title,
                            onChange: function (value) {
                                setAttributes({ section1Title: value });
                            },
                        }),

                        el(TextareaControl, {
                            label: __('Description', 'sunnytree'),
                            value: attributes.section1Description,
                            onChange: function (value) {
                                setAttributes({ section1Description: value });
                            },
                            rows: 3,
                        }),

                        el(TextControl, {
                            label: __('Link Text', 'sunnytree'),
                            value: attributes.section1LinkText,
                            onChange: function (value) {
                                setAttributes({ section1LinkText: value });
                            },
                        }),

                        el(
                            'div',
                            { className: 'components-base-control' },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Link URL', 'sunnytree')
                            ),
                            el(URLInput, {
                                value: attributes.section1LinkUrl,
                                onChange: function (value) {
                                    setAttributes({ section1LinkUrl: value });
                                },
                            })
                        ),

                        el(SelectControl, {
                            label: __('Link Target', 'sunnytree'),
                            value: attributes.section1LinkTarget,
                            options: [
                                { label: __('Same Window', 'sunnytree'), value: '_self' },
                                { label: __('New Tab', 'sunnytree'), value: '_blank' },
                            ],
                            onChange: function (value) {
                                setAttributes({ section1LinkTarget: value });
                            },
                        })
                    ),

                    // Section 2 Panel
                    el(
                        PanelBody,
                        {
                            title: __('Section 2 (Top Right - Image Only)', 'sunnytree'),
                            initialOpen: false,
                        },

                        MediaUploadControl({
                            label: __('Image', 'sunnytree'),
                            mediaId: attributes.section2ImageId,
                            mediaUrl: attributes.section2ImageUrl,
                            onSelect: onSelectSection2Image,
                            onRemove: onRemoveSection2Image,
                        }),

                        attributes.section2ImageUrl &&
                            el(TextControl, {
                                label: __('Image Alt Text', 'sunnytree'),
                                value: attributes.section2ImageAlt,
                                onChange: function (value) {
                                    setAttributes({ section2ImageAlt: value });
                                },
                            })
                    ),

                    // Section 3 Panel
                    el(
                        PanelBody,
                        {
                            title: __('Section 3 (Bottom Right)', 'sunnytree'),
                            initialOpen: false,
                        },

                        MediaUploadControl({
                            label: __('Background Image', 'sunnytree'),
                            mediaId: attributes.section3ImageId,
                            mediaUrl: attributes.section3ImageUrl,
                            onSelect: onSelectSection3Image,
                            onRemove: onRemoveSection3Image,
                        }),

                        attributes.section3ImageUrl &&
                            el(TextControl, {
                                label: __('Image Alt Text', 'sunnytree'),
                                value: attributes.section3ImageAlt,
                                onChange: function (value) {
                                    setAttributes({ section3ImageAlt: value });
                                },
                            }),

                        el(TextControl, {
                            label: __('Title', 'sunnytree'),
                            value: attributes.section3Title,
                            onChange: function (value) {
                                setAttributes({ section3Title: value });
                            },
                        }),

                        el(TextareaControl, {
                            label: __('Description', 'sunnytree'),
                            value: attributes.section3Description,
                            onChange: function (value) {
                                setAttributes({ section3Description: value });
                            },
                            rows: 3,
                        }),

                        el(TextControl, {
                            label: __('Link Text', 'sunnytree'),
                            value: attributes.section3LinkText,
                            onChange: function (value) {
                                setAttributes({ section3LinkText: value });
                            },
                        }),

                        el(
                            'div',
                            { className: 'components-base-control' },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Link URL', 'sunnytree')
                            ),
                            el(URLInput, {
                                value: attributes.section3LinkUrl,
                                onChange: function (value) {
                                    setAttributes({ section3LinkUrl: value });
                                },
                            })
                        ),

                        el(SelectControl, {
                            label: __('Link Target', 'sunnytree'),
                            value: attributes.section3LinkTarget,
                            options: [
                                { label: __('Same Window', 'sunnytree'), value: '_self' },
                                { label: __('New Tab', 'sunnytree'), value: '_blank' },
                            ],
                            onChange: function (value) {
                                setAttributes({ section3LinkTarget: value });
                            },
                        })
                    )
                ),

                // Editor Preview
                el(
                    'div',
                    blockProps,

                    el(
                        'div',
                        { className: 'sunnytree-hero-grid__container' },

                        // Section 1 - Large Left
                        el(
                            'div',
                            { className: 'sunnytree-hero-grid__section sunnytree-hero-grid__section--1' },
                            attributes.section1ImageUrl
                                ? el(
                                      'div',
                                      {
                                          className: 'sunnytree-hero-grid__section-inner',
                                          style: {
                                              backgroundImage: 'url(' + attributes.section1ImageUrl + ')',
                                              backgroundSize: 'cover',
                                              backgroundPosition: 'center',
                                          },
                                      },
                                      el(
                                          'div',
                                          { className: 'sunnytree-hero-grid__content' },
                                          el(RichText, {
                                              tagName: 'h2',
                                              className: 'sunnytree-hero-grid__title',
                                              placeholder: __('Enter title...', 'sunnytree'),
                                              value: attributes.section1Title,
                                              onChange: function (value) {
                                                  setAttributes({ section1Title: value });
                                              },
                                              allowedFormats: ['core/bold', 'core/italic'],
                                          }),
                                          el(RichText, {
                                              tagName: 'p',
                                              className: 'sunnytree-hero-grid__description',
                                              placeholder: __('Enter description...', 'sunnytree'),
                                              value: attributes.section1Description,
                                              onChange: function (value) {
                                                  setAttributes({ section1Description: value });
                                              },
                                          }),
                                          attributes.section1LinkText &&
                                              el(
                                                  'span',
                                                  { className: 'sunnytree-hero-grid__link' },
                                                  attributes.section1LinkText
                                              )
                                      )
                                  )
                                : el(
                                      Placeholder,
                                      {
                                          icon: 'format-image',
                                          label: __('Section 1', 'sunnytree'),
                                          instructions: __('Upload an image from the sidebar.', 'sunnytree'),
                                      }
                                  )
                        ),

                        // Right Column
                        el(
                            'div',
                            { className: 'sunnytree-hero-grid__right-column' },

                            // Section 2 - Top Right (Image Only)
                            el(
                                'div',
                                { className: 'sunnytree-hero-grid__section sunnytree-hero-grid__section--2' },
                                attributes.section2ImageUrl
                                    ? el('img', {
                                          src: attributes.section2ImageUrl,
                                          alt: attributes.section2ImageAlt,
                                          className: 'sunnytree-hero-grid__image',
                                      })
                                    : el(
                                          Placeholder,
                                          {
                                              icon: 'format-image',
                                              label: __('Section 2', 'sunnytree'),
                                              instructions: __('Image only section.', 'sunnytree'),
                                          }
                                      )
                            ),

                            // Section 3 - Bottom Right
                            el(
                                'div',
                                { className: 'sunnytree-hero-grid__section sunnytree-hero-grid__section--3' },
                                el(
                                    'div',
                                    {
                                        className: 'sunnytree-hero-grid__section-inner',
                                        style: attributes.section3ImageUrl
                                            ? {
                                                  backgroundImage: 'url(' + attributes.section3ImageUrl + ')',
                                                  backgroundSize: 'cover',
                                                  backgroundPosition: 'center',
                                              }
                                            : {},
                                    },
                                    el(
                                        'div',
                                        { className: 'sunnytree-hero-grid__content' },
                                        el(RichText, {
                                            tagName: 'h3',
                                            className: 'sunnytree-hero-grid__title',
                                            placeholder: __('Enter title...', 'sunnytree'),
                                            value: attributes.section3Title,
                                            onChange: function (value) {
                                                setAttributes({ section3Title: value });
                                            },
                                            allowedFormats: ['core/bold', 'core/italic'],
                                        }),
                                        el(RichText, {
                                            tagName: 'p',
                                            className: 'sunnytree-hero-grid__description',
                                            placeholder: __('Enter description...', 'sunnytree'),
                                            value: attributes.section3Description,
                                            onChange: function (value) {
                                                setAttributes({ section3Description: value });
                                            },
                                        })
                                    )
                                )
                            )
                        )
                    )
                )
            );
        },

        save: function () {
            return null;
        },
    });
})(window.wp);

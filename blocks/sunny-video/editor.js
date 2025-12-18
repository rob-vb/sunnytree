/**
 * Sunny Video Block - Editor Script
 *
 * @package SunnyTree
 */

(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var __ = wp.i18n.__;

    // Block Editor Components
    var useBlockProps = wp.blockEditor.useBlockProps;
    var RichText = wp.blockEditor.RichText;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
    var URLInput = wp.blockEditor.URLInput;

    // WordPress Components
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var Button = wp.components.Button;
    var Flex = wp.components.Flex;
    var FlexItem = wp.components.FlexItem;
    var Placeholder = wp.components.Placeholder;

    registerBlockType('sunnytree/sunny-video', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var videoId = attributes.videoId;
            var videoUrl = attributes.videoUrl;
            var posterImageId = attributes.posterImageId;
            var posterImageUrl = attributes.posterImageUrl;
            var heading = attributes.heading;
            var content = attributes.content;
            var buttonText = attributes.buttonText;
            var buttonUrl = attributes.buttonUrl;
            var buttonTarget = attributes.buttonTarget;

            var blockProps = useBlockProps({
                className: 'sunny-premium-content-main-wrapper',
            });

            // Video handlers
            var onSelectVideo = function (media) {
                setAttributes({
                    videoId: media.id,
                    videoUrl: media.url,
                });
            };

            var onRemoveVideo = function () {
                setAttributes({
                    videoId: 0,
                    videoUrl: '',
                });
            };

            // Poster image handlers
            var onSelectPoster = function (media) {
                setAttributes({
                    posterImageId: media.id,
                    posterImageUrl: media.url,
                });
            };

            var onRemovePoster = function () {
                setAttributes({
                    posterImageId: 0,
                    posterImageUrl: '',
                });
            };

            return el(
                Fragment,
                null,

                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    null,

                    // Video Settings Panel
                    el(
                        PanelBody,
                        {
                            title: __('Video Settings', 'sunnytree'),
                            initialOpen: true,
                        },

                        // Video Upload
                        el(
                            'div',
                            { className: 'components-base-control' },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Video', 'sunnytree')
                            ),
                            el(
                                MediaUploadCheck,
                                null,
                                el(MediaUpload, {
                                    onSelect: onSelectVideo,
                                    allowedTypes: ['video'],
                                    value: videoId,
                                    render: function (obj) {
                                        return el(
                                            'div',
                                            { className: 'editor-media-upload' },
                                            videoUrl
                                                ? el(
                                                      Fragment,
                                                      null,
                                                      el('video', {
                                                          src: videoUrl,
                                                          style: {
                                                              maxWidth: '100%',
                                                              marginBottom: '10px',
                                                              display: 'block',
                                                          },
                                                          controls: true,
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
                                                                      onClick: onRemoveVideo,
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
                                                      __('Select Video', 'sunnytree')
                                                  )
                                        );
                                    },
                                })
                            )
                        ),

                        // Poster Image Upload
                        el(
                            'div',
                            { className: 'components-base-control', style: { marginTop: '16px' } },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Poster Image (Thumbnail)', 'sunnytree')
                            ),
                            el(
                                MediaUploadCheck,
                                null,
                                el(MediaUpload, {
                                    onSelect: onSelectPoster,
                                    allowedTypes: ['image'],
                                    value: posterImageId,
                                    render: function (obj) {
                                        return el(
                                            'div',
                                            { className: 'editor-media-upload' },
                                            posterImageUrl
                                                ? el(
                                                      Fragment,
                                                      null,
                                                      el('img', {
                                                          src: posterImageUrl,
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
                                                                      onClick: onRemovePoster,
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
                                                      __('Select Poster Image', 'sunnytree')
                                                  )
                                        );
                                    },
                                })
                            )
                        )
                    ),

                    // Button Settings Panel
                    el(
                        PanelBody,
                        {
                            title: __('Button Settings', 'sunnytree'),
                            initialOpen: false,
                        },

                        el(TextControl, {
                            label: __('Button Text', 'sunnytree'),
                            value: buttonText,
                            onChange: function (value) {
                                setAttributes({ buttonText: value });
                            },
                        }),

                        el(
                            'div',
                            { className: 'components-base-control' },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Button URL', 'sunnytree')
                            ),
                            el(URLInput, {
                                value: buttonUrl,
                                onChange: function (value) {
                                    setAttributes({ buttonUrl: value });
                                },
                            })
                        ),

                        el(SelectControl, {
                            label: __('Link Target', 'sunnytree'),
                            value: buttonTarget,
                            options: [
                                { label: __('Same Window', 'sunnytree'), value: '_self' },
                                { label: __('New Tab', 'sunnytree'), value: '_blank' },
                            ],
                            onChange: function (value) {
                                setAttributes({ buttonTarget: value });
                            },
                        })
                    )
                ),

                // Block Content (Editor View)
                el(
                    'div',
                    blockProps,

                    // Video Section (Left)
                    el(
                        'div',
                        { className: 'sunny-premium-content-image' },
                        videoUrl
                            ? el('video', {
                                  src: videoUrl,
                                  poster: posterImageUrl || undefined,
                                  controls: true,
                                  muted: true,
                                  style: { width: '100%' },
                              })
                            : el(
                                  Placeholder,
                                  {
                                      icon: 'video-alt3',
                                      label: __('Sunny Video', 'sunnytree'),
                                      instructions: __('Upload a video from the sidebar.', 'sunnytree'),
                                  }
                              )
                    ),

                    // Content Section (Right)
                    el(
                        'div',
                        { className: 'sunny-premium-content-wrapper' },

                        // Heading
                        el(RichText, {
                            tagName: 'h3',
                            className: 'sunny-premium-content-titel',
                            placeholder: __('Enter heading...', 'sunnytree'),
                            value: heading,
                            onChange: function (value) {
                                setAttributes({ heading: value });
                            },
                            allowedFormats: ['core/bold', 'core/italic'],
                        }),

                        // Content
                        el(RichText, {
                            tagName: 'div',
                            className: 'sunny-premium-content-text',
                            placeholder: __('Enter content...', 'sunnytree'),
                            value: content,
                            onChange: function (value) {
                                setAttributes({ content: value });
                            },
                            multiline: 'p',
                            allowedFormats: [
                                'core/bold',
                                'core/italic',
                                'core/link',
                                'core/strikethrough',
                            ],
                        }),

                        // Button Preview
                        buttonText &&
                            el(
                                'span',
                                { className: 'sunny-premium-content-button' },
                                buttonText
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

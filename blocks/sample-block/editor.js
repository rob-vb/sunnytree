/**
 * Sample Block - Editor Script
 *
 * Comprehensive reference demonstrating all available controls and components.
 *
 * @package SunnyTree
 */

(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var useState = wp.element.useState;
    var __ = wp.i18n.__;

    // Block Editor Components
    var useBlockProps = wp.blockEditor.useBlockProps;
    var RichText = wp.blockEditor.RichText;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var BlockControls = wp.blockEditor.BlockControls;
    var AlignmentToolbar = wp.blockEditor.AlignmentToolbar;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
    var URLInput = wp.blockEditor.URLInput;
    var InnerBlocks = wp.blockEditor.InnerBlocks;
    var BlockVerticalAlignmentToolbar = wp.blockEditor.BlockVerticalAlignmentToolbar;

    // WordPress Components
    var PanelBody = wp.components.PanelBody;
    var PanelRow = wp.components.PanelRow;
    var TextControl = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var ToggleControl = wp.components.ToggleControl;
    var SelectControl = wp.components.SelectControl;
    var RangeControl = wp.components.RangeControl;
    var ColorPicker = wp.components.ColorPicker;
    var ColorPalette = wp.components.ColorPalette;
    var Button = wp.components.Button;
    var ButtonGroup = wp.components.ButtonGroup;
    var IconButton = wp.components.IconButton;
    var Toolbar = wp.components.Toolbar;
    var ToolbarButton = wp.components.ToolbarButton;
    var ToolbarGroup = wp.components.ToolbarGroup;
    var Placeholder = wp.components.Placeholder;
    var Spinner = wp.components.Spinner;
    var Notice = wp.components.Notice;
    var CheckboxControl = wp.components.CheckboxControl;
    var RadioControl = wp.components.RadioControl;
    var FormTokenField = wp.components.FormTokenField;
    var DateTimePicker = wp.components.DateTimePicker;
    var TimePicker = wp.components.TimePicker;
    var Popover = wp.components.Popover;
    var Modal = wp.components.Modal;
    var Card = wp.components.Card;
    var CardBody = wp.components.CardBody;
    var CardHeader = wp.components.CardHeader;
    var Tip = wp.components.Tip;
    var Disabled = wp.components.Disabled;
    var ExternalLink = wp.components.ExternalLink;
    var FocalPointPicker = wp.components.FocalPointPicker;
    var FontSizePicker = wp.components.FontSizePicker;
    var AnglePickerControl = wp.components.AnglePickerControl;
    var UnitControl = wp.components.__experimentalUnitControl;
    var BoxControl = wp.components.__experimentalBoxControl;
    var NumberControl = wp.components.__experimentalNumberControl;
    var Flex = wp.components.Flex;
    var FlexItem = wp.components.FlexItem;
    var FlexBlock = wp.components.FlexBlock;

    /**
     * Register the block
     */
    registerBlockType('sunnytree/sample-block', {
        /**
         * Edit function - renders the block in the editor
         */
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var isSelected = props.isSelected;
            var clientId = props.clientId;

            // Destructure all attributes
            var title = attributes.title;
            var content = attributes.content;
            var richContent = attributes.richContent;
            var showTitle = attributes.showTitle;
            var showContent = attributes.showContent;
            var columns = attributes.columns;
            var gap = attributes.gap;
            var aspectRatio = attributes.aspectRatio;
            var mediaId = attributes.mediaId;
            var mediaUrl = attributes.mediaUrl;
            var mediaAlt = attributes.mediaAlt;
            var linkUrl = attributes.linkUrl;
            var linkTarget = attributes.linkTarget;
            var linkRel = attributes.linkRel;
            var buttonText = attributes.buttonText;
            var buttonStyle = attributes.buttonStyle;
            var selectedItems = attributes.selectedItems;
            var items = attributes.items;
            var settings = attributes.settings;
            var verticalAlignment = attributes.verticalAlignment;
            var horizontalAlignment = attributes.horizontalAlignment;
            var iconName = attributes.iconName;
            var iconSize = attributes.iconSize;
            var date = attributes.date;
            var customClassName = attributes.customClassName;

            // Local state for modal
            var _useState = useState(false);
            var isModalOpen = _useState[0];
            var setIsModalOpen = _useState[1];

            // Block props with dynamic classes
            var blockProps = useBlockProps({
                className: [
                    'sunnytree-sample-block',
                    'has-columns-' + columns,
                    'valign-' + verticalAlignment,
                    'halign-' + horizontalAlignment,
                    customClassName,
                ]
                    .filter(Boolean)
                    .join(' '),
                style: {
                    '--columns': columns,
                    '--gap': gap + 'px',
                },
            });

            // Media upload handler
            var onSelectMedia = function (media) {
                setAttributes({
                    mediaId: media.id,
                    mediaUrl: media.url,
                    mediaAlt: media.alt || '',
                });
            };

            var onRemoveMedia = function () {
                setAttributes({
                    mediaId: 0,
                    mediaUrl: '',
                    mediaAlt: '',
                });
            };

            // Helper to update nested settings object
            var updateSettings = function (key, value) {
                setAttributes({
                    settings: Object.assign({}, settings, { [key]: value }),
                });
            };

            return el(
                Fragment,
                null,

                // ================================================
                // BLOCK TOOLBAR CONTROLS
                // ================================================
                el(
                    BlockControls,
                    { group: 'block' },

                    // Alignment Toolbar
                    el(AlignmentToolbar, {
                        value: horizontalAlignment,
                        onChange: function (value) {
                            setAttributes({ horizontalAlignment: value || 'left' });
                        },
                    }),

                    // Vertical Alignment Toolbar
                    el(BlockVerticalAlignmentToolbar, {
                        value: verticalAlignment,
                        onChange: function (value) {
                            setAttributes({ verticalAlignment: value || 'top' });
                        },
                    }),

                    // Custom Toolbar Group
                    el(
                        ToolbarGroup,
                        null,
                        el(ToolbarButton, {
                            icon: 'visibility',
                            label: __('Toggle Title', 'sunnytree'),
                            isPressed: showTitle,
                            onClick: function () {
                                setAttributes({ showTitle: !showTitle });
                            },
                        }),
                        el(ToolbarButton, {
                            icon: 'text',
                            label: __('Toggle Content', 'sunnytree'),
                            isPressed: showContent,
                            onClick: function () {
                                setAttributes({ showContent: !showContent });
                            },
                        })
                    )
                ),

                // ================================================
                // INSPECTOR CONTROLS (SIDEBAR)
                // ================================================
                el(
                    InspectorControls,
                    null,

                    // ------------------------------------------
                    // PANEL: Content Settings
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Content Settings', 'sunnytree'),
                            initialOpen: true,
                        },

                        // TextControl - Single line text input
                        el(TextControl, {
                            label: __('Title', 'sunnytree'),
                            value: title,
                            onChange: function (value) {
                                setAttributes({ title: value });
                            },
                            help: __('Enter a title for the block.', 'sunnytree'),
                        }),

                        // TextareaControl - Multi-line text input
                        el(TextareaControl, {
                            label: __('Content', 'sunnytree'),
                            value: content,
                            onChange: function (value) {
                                setAttributes({ content: value });
                            },
                            rows: 4,
                            help: __('Enter content for the block.', 'sunnytree'),
                        }),

                        // ToggleControl - Boolean switch
                        el(ToggleControl, {
                            label: __('Show Title', 'sunnytree'),
                            checked: showTitle,
                            onChange: function (value) {
                                setAttributes({ showTitle: value });
                            },
                            help: showTitle
                                ? __('Title is visible.', 'sunnytree')
                                : __('Title is hidden.', 'sunnytree'),
                        }),

                        el(ToggleControl, {
                            label: __('Show Content', 'sunnytree'),
                            checked: showContent,
                            onChange: function (value) {
                                setAttributes({ showContent: value });
                            },
                        })
                    ),

                    // ------------------------------------------
                    // PANEL: Layout Settings
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Layout Settings', 'sunnytree'),
                            initialOpen: false,
                        },

                        // RangeControl - Numeric slider
                        el(RangeControl, {
                            label: __('Columns', 'sunnytree'),
                            value: columns,
                            onChange: function (value) {
                                setAttributes({ columns: value });
                            },
                            min: 1,
                            max: 6,
                            step: 1,
                            marks: [
                                { value: 1, label: '1' },
                                { value: 2, label: '2' },
                                { value: 3, label: '3' },
                                { value: 4, label: '4' },
                                { value: 6, label: '6' },
                            ],
                            withInputField: true,
                            help: __('Number of columns in the grid.', 'sunnytree'),
                        }),

                        el(RangeControl, {
                            label: __('Gap (px)', 'sunnytree'),
                            value: gap,
                            onChange: function (value) {
                                setAttributes({ gap: value });
                            },
                            min: 0,
                            max: 100,
                            step: 5,
                        }),

                        // SelectControl - Dropdown select
                        el(SelectControl, {
                            label: __('Aspect Ratio', 'sunnytree'),
                            value: aspectRatio,
                            options: [
                                { label: __('Auto', 'sunnytree'), value: 'auto' },
                                { label: '1:1', value: '1/1' },
                                { label: '4:3', value: '4/3' },
                                { label: '16:9', value: '16/9' },
                                { label: '21:9', value: '21/9' },
                                { label: '3:4', value: '3/4' },
                                { label: '9:16', value: '9/16' },
                            ],
                            onChange: function (value) {
                                setAttributes({ aspectRatio: value });
                            },
                        }),

                        // RadioControl - Radio buttons
                        el(RadioControl, {
                            label: __('Vertical Alignment', 'sunnytree'),
                            selected: verticalAlignment,
                            options: [
                                { label: __('Top', 'sunnytree'), value: 'top' },
                                { label: __('Center', 'sunnytree'), value: 'center' },
                                { label: __('Bottom', 'sunnytree'), value: 'bottom' },
                            ],
                            onChange: function (value) {
                                setAttributes({ verticalAlignment: value });
                            },
                        }),

                        // ButtonGroup - Button toggle group
                        el(
                            'div',
                            { className: 'components-base-control' },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Horizontal Alignment', 'sunnytree')
                            ),
                            el(
                                ButtonGroup,
                                null,
                                el(
                                    Button,
                                    {
                                        variant:
                                            horizontalAlignment === 'left' ? 'primary' : 'secondary',
                                        onClick: function () {
                                            setAttributes({ horizontalAlignment: 'left' });
                                        },
                                    },
                                    __('Left', 'sunnytree')
                                ),
                                el(
                                    Button,
                                    {
                                        variant:
                                            horizontalAlignment === 'center'
                                                ? 'primary'
                                                : 'secondary',
                                        onClick: function () {
                                            setAttributes({ horizontalAlignment: 'center' });
                                        },
                                    },
                                    __('Center', 'sunnytree')
                                ),
                                el(
                                    Button,
                                    {
                                        variant:
                                            horizontalAlignment === 'right'
                                                ? 'primary'
                                                : 'secondary',
                                        onClick: function () {
                                            setAttributes({ horizontalAlignment: 'right' });
                                        },
                                    },
                                    __('Right', 'sunnytree')
                                )
                            )
                        )
                    ),

                    // ------------------------------------------
                    // PANEL: Media Settings
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Media Settings', 'sunnytree'),
                            initialOpen: false,
                        },

                        // MediaUpload - Image/file selector
                        el(
                            'div',
                            { className: 'components-base-control' },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Featured Image', 'sunnytree')
                            ),
                            el(
                                MediaUploadCheck,
                                null,
                                el(MediaUpload, {
                                    onSelect: onSelectMedia,
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
                                                          alt: mediaAlt,
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
                                                                      onClick: onRemoveMedia,
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
                        ),

                        mediaUrl &&
                            el(TextControl, {
                                label: __('Alt Text', 'sunnytree'),
                                value: mediaAlt,
                                onChange: function (value) {
                                    setAttributes({ mediaAlt: value });
                                },
                                help: __('Describe the image for accessibility.', 'sunnytree'),
                            })
                    ),

                    // ------------------------------------------
                    // PANEL: Link Settings
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Link Settings', 'sunnytree'),
                            initialOpen: false,
                        },

                        // URLInput - URL input field
                        el(
                            'div',
                            { className: 'components-base-control' },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Link URL', 'sunnytree')
                            ),
                            el(URLInput, {
                                value: linkUrl,
                                onChange: function (value) {
                                    setAttributes({ linkUrl: value });
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
                                setAttributes({ linkTarget: value });
                            },
                        }),

                        el(TextControl, {
                            label: __('Link Rel', 'sunnytree'),
                            value: linkRel,
                            onChange: function (value) {
                                setAttributes({ linkRel: value });
                            },
                            help: __('e.g., nofollow, noopener', 'sunnytree'),
                        })
                    ),

                    // ------------------------------------------
                    // PANEL: Button Settings
                    // ------------------------------------------
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

                        el(SelectControl, {
                            label: __('Button Style', 'sunnytree'),
                            value: buttonStyle,
                            options: [
                                { label: __('Primary', 'sunnytree'), value: 'primary' },
                                { label: __('Secondary', 'sunnytree'), value: 'secondary' },
                                { label: __('Outline', 'sunnytree'), value: 'outline' },
                                { label: __('Ghost', 'sunnytree'), value: 'ghost' },
                            ],
                            onChange: function (value) {
                                setAttributes({ buttonStyle: value });
                            },
                        })
                    ),

                    // ------------------------------------------
                    // PANEL: Icon Settings
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Icon Settings', 'sunnytree'),
                            initialOpen: false,
                        },

                        el(SelectControl, {
                            label: __('Icon', 'sunnytree'),
                            value: iconName,
                            options: [
                                { label: __('Star', 'sunnytree'), value: 'star' },
                                { label: __('Heart', 'sunnytree'), value: 'heart' },
                                { label: __('Check', 'sunnytree'), value: 'check' },
                                { label: __('Arrow Right', 'sunnytree'), value: 'arrow-right' },
                                { label: __('Info', 'sunnytree'), value: 'info' },
                                { label: __('Warning', 'sunnytree'), value: 'warning' },
                            ],
                            onChange: function (value) {
                                setAttributes({ iconName: value });
                            },
                        }),

                        el(RangeControl, {
                            label: __('Icon Size', 'sunnytree'),
                            value: iconSize,
                            onChange: function (value) {
                                setAttributes({ iconSize: value });
                            },
                            min: 12,
                            max: 64,
                            step: 4,
                        })
                    ),

                    // ------------------------------------------
                    // PANEL: Tags/Tokens
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Tags / Multi-Select', 'sunnytree'),
                            initialOpen: false,
                        },

                        // FormTokenField - Tag input
                        el(FormTokenField, {
                            label: __('Tags', 'sunnytree'),
                            value: selectedItems,
                            suggestions: [
                                'Featured',
                                'Popular',
                                'New',
                                'Sale',
                                'Limited',
                                'Exclusive',
                            ],
                            onChange: function (tokens) {
                                setAttributes({ selectedItems: tokens });
                            },
                            __experimentalExpandOnFocus: true,
                            __experimentalShowHowTo: false,
                        })
                    ),

                    // ------------------------------------------
                    // PANEL: Date & Time
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Date & Time', 'sunnytree'),
                            initialOpen: false,
                        },

                        // DateTimePicker
                        el(
                            'div',
                            { className: 'components-base-control' },
                            el(
                                'label',
                                { className: 'components-base-control__label' },
                                __('Select Date', 'sunnytree')
                            ),
                            el(DateTimePicker, {
                                currentDate: date || undefined,
                                onChange: function (value) {
                                    setAttributes({ date: value });
                                },
                                is12Hour: true,
                            })
                        )
                    ),

                    // ------------------------------------------
                    // PANEL: Advanced Object Settings
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Advanced Settings (Object)', 'sunnytree'),
                            initialOpen: false,
                        },

                        el(
                            Notice,
                            {
                                status: 'info',
                                isDismissible: false,
                            },
                            __('These settings demonstrate nested object attributes.', 'sunnytree')
                        ),

                        el(ToggleControl, {
                            label: __('Autoplay', 'sunnytree'),
                            checked: settings.autoplay,
                            onChange: function (value) {
                                updateSettings('autoplay', value);
                            },
                        }),

                        el(RangeControl, {
                            label: __('Animation Speed (ms)', 'sunnytree'),
                            value: settings.speed,
                            onChange: function (value) {
                                updateSettings('speed', value);
                            },
                            min: 100,
                            max: 2000,
                            step: 100,
                        }),

                        el(ToggleControl, {
                            label: __('Loop', 'sunnytree'),
                            checked: settings.loop,
                            onChange: function (value) {
                                updateSettings('loop', value);
                            },
                        })
                    ),

                    // ------------------------------------------
                    // PANEL: Custom CSS Class
                    // ------------------------------------------
                    el(
                        PanelBody,
                        {
                            title: __('Custom Class', 'sunnytree'),
                            initialOpen: false,
                        },

                        el(TextControl, {
                            label: __('Additional CSS Class', 'sunnytree'),
                            value: customClassName,
                            onChange: function (value) {
                                setAttributes({ customClassName: value });
                            },
                            help: __('Add custom CSS class(es) to the block.', 'sunnytree'),
                        })
                    )
                ),

                // ================================================
                // BLOCK CONTENT (EDITOR VIEW)
                // ================================================
                el(
                    'div',
                    blockProps,

                    el(
                        'div',
                        { className: 'sunnytree-sample-block__inner' },

                        // Media
                        mediaUrl &&
                            el(
                                'div',
                                { className: 'sunnytree-sample-block__media' },
                                el('img', {
                                    src: mediaUrl,
                                    alt: mediaAlt,
                                    className: 'sunnytree-sample-block__image',
                                })
                            ),

                        // Content wrapper
                        el(
                            'div',
                            { className: 'sunnytree-sample-block__content-wrapper' },

                            // Title (RichText)
                            showTitle &&
                                el(RichText, {
                                    tagName: 'h3',
                                    className: 'sunnytree-sample-block__title',
                                    placeholder: __('Enter title...', 'sunnytree'),
                                    value: title,
                                    onChange: function (value) {
                                        setAttributes({ title: value });
                                    },
                                    allowedFormats: ['core/bold', 'core/italic'],
                                }),

                            // Content (RichText with more formats)
                            showContent &&
                                el(RichText, {
                                    tagName: 'div',
                                    className: 'sunnytree-sample-block__content',
                                    placeholder: __('Enter content...', 'sunnytree'),
                                    value: content,
                                    onChange: function (value) {
                                        setAttributes({ content: value });
                                    },
                                    allowedFormats: [
                                        'core/bold',
                                        'core/italic',
                                        'core/link',
                                        'core/strikethrough',
                                        'core/code',
                                    ],
                                }),

                            // Rich Content (HTML source)
                            el(RichText, {
                                tagName: 'div',
                                className: 'sunnytree-sample-block__rich-content',
                                placeholder: __('Rich content (saved as HTML)...', 'sunnytree'),
                                value: richContent,
                                onChange: function (value) {
                                    setAttributes({ richContent: value });
                                },
                                multiline: 'p',
                            }),

                            // Button
                            buttonText &&
                                el(
                                    'div',
                                    { className: 'sunnytree-sample-block__button-wrapper' },
                                    el(
                                        'span',
                                        {
                                            className:
                                                'sunnytree-sample-block__button sunnytree-sample-block__button--' +
                                                buttonStyle,
                                        },
                                        buttonText
                                    )
                                )
                        )
                    ),

                    // Tags display
                    selectedItems.length > 0 &&
                        el(
                            'div',
                            { className: 'sunnytree-sample-block__tags' },
                            selectedItems.map(function (tag, index) {
                                return el(
                                    'span',
                                    {
                                        key: index,
                                        className: 'sunnytree-sample-block__tag',
                                    },
                                    tag
                                );
                            })
                        ),

                    // Settings display (for debugging/reference)
                    isSelected &&
                        el(
                            'div',
                            { className: 'sunnytree-sample-block__debug' },
                            el(
                                'details',
                                null,
                                el('summary', null, __('Debug: Current Settings', 'sunnytree')),
                                el(
                                    'pre',
                                    null,
                                    JSON.stringify(
                                        {
                                            columns: columns,
                                            gap: gap,
                                            verticalAlignment: verticalAlignment,
                                            horizontalAlignment: horizontalAlignment,
                                            settings: settings,
                                        },
                                        null,
                                        2
                                    )
                                )
                            )
                        )
                )
            );
        },

        /**
         * Save function - returns null for dynamic (PHP-rendered) blocks
         *
         * For static blocks, you would return the markup here instead.
         * Since we use render.php, we return null.
         */
        save: function () {
            return null;
        },
    });
})(window.wp);

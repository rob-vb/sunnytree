/**
 * Wrapper Block - Editor Script
 *
 * @package SunnyTree
 */

(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var __ = wp.i18n.__;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var InnerBlocks = wp.blockEditor.InnerBlocks;

    registerBlockType('sunnytree/wrapper', {
        edit: function () {
            var blockProps = useBlockProps({
                className: 'container',
            });

            return el(
                'div',
                blockProps,
                el(InnerBlocks, {
                    template: [['core/paragraph', { placeholder: __('Add content...', 'sunnytree') }]],
                    templateLock: false,
                })
            );
        },

        save: function () {
            return el(InnerBlocks.Content);
        },
    });
})(window.wp);

/**
 * Product Grid Block - Editor Script
 *
 * @package SunnyTree
 */

(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var useState = wp.element.useState;
    var useEffect = wp.element.useEffect;
    var __ = wp.i18n.__;

    var useBlockProps = wp.blockEditor.useBlockProps;
    var InspectorControls = wp.blockEditor.InspectorControls;

    var PanelBody = wp.components.PanelBody;
    var RangeControl = wp.components.RangeControl;
    var SelectControl = wp.components.SelectControl;
    var ToggleControl = wp.components.ToggleControl;
    var Spinner = wp.components.Spinner;
    var FormTokenField = wp.components.FormTokenField;
    var Placeholder = wp.components.Placeholder;

    var apiFetch = wp.apiFetch;

    registerBlockType('sunnytree/product-grid', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var productIds = attributes.productIds || [];
            var columns = attributes.columns;
            var displayType = attributes.displayType;
            var category = attributes.category;
            var limit = attributes.limit;
            var orderBy = attributes.orderBy;
            var order = attributes.order;
            var onSale = attributes.onSale;
            var featured = attributes.featured;

            var _useState = useState([]);
            var products = _useState[0];
            var setProducts = _useState[1];

            var _useState2 = useState([]);
            var categories = _useState2[0];
            var setCategories = _useState2[1];

            var _useState3 = useState([]);
            var allProducts = _useState3[0];
            var setAllProducts = _useState3[1];

            var _useState4 = useState(true);
            var isLoading = _useState4[0];
            var setIsLoading = _useState4[1];

            var _useState5 = useState('');
            var searchTerm = _useState5[0];
            var setSearchTerm = _useState5[1];

            var blockProps = useBlockProps({
                className: 'sunnytree-product-grid columns-' + columns,
            });

            // Fetch categories on mount
            useEffect(function () {
                apiFetch({ path: '/wc/v3/products/categories?per_page=100' })
                    .then(function (data) {
                        setCategories(data.map(function (cat) {
                            return { label: cat.name, value: cat.slug };
                        }));
                    })
                    .catch(function () {
                        setCategories([]);
                    });
            }, []);

            // Fetch all products for the token field
            useEffect(function () {
                apiFetch({ path: '/wc/v3/products?per_page=100&status=publish' })
                    .then(function (data) {
                        setAllProducts(data);
                    })
                    .catch(function () {
                        setAllProducts([]);
                    });
            }, []);

            // Fetch products for preview
            useEffect(function () {
                setIsLoading(true);
                var path = '/wc/v3/products?per_page=' + limit + '&status=publish';

                if (displayType === 'selected' && productIds.length > 0) {
                    path = '/wc/v3/products?include=' + productIds.join(',') + '&status=publish';
                } else if (displayType === 'category' && category) {
                    path += '&category=' + category;
                } else if (displayType === 'featured') {
                    path += '&featured=true';
                } else if (displayType === 'on_sale') {
                    path += '&on_sale=true';
                }

                path += '&orderby=' + orderBy + '&order=' + order;

                apiFetch({ path: path })
                    .then(function (data) {
                        setProducts(data);
                        setIsLoading(false);
                    })
                    .catch(function () {
                        setProducts([]);
                        setIsLoading(false);
                    });
            }, [productIds, displayType, category, limit, orderBy, order, onSale, featured]);

            // Get product names for token field
            var productNames = allProducts.map(function (p) {
                return p.name;
            });

            // Get selected product names
            var selectedProductNames = productIds
                .map(function (id) {
                    var found = allProducts.find(function (p) {
                        return p.id === id;
                    });
                    return found ? found.name : null;
                })
                .filter(Boolean);

            // Handle token field changes
            function onProductsChange(tokens) {
                var ids = tokens
                    .map(function (name) {
                        var found = allProducts.find(function (p) {
                            return p.name === name;
                        });
                        return found ? found.id : null;
                    })
                    .filter(Boolean);
                setAttributes({ productIds: ids });
            }

            // Create product card preview
            function createProductCard(product) {
                var isOnSale = product.on_sale;
                var regularPrice = product.regular_price;
                var salePrice = product.sale_price;
                var currentPrice = product.price;
                var imageUrl = product.images && product.images[0] ? product.images[0].src : '';

                return el(
                    'div',
                    { className: 'sunny-product', key: product.id },
                    isOnSale && el(
                        'div',
                        { className: 'sale-label' },
                        el('span', null, __('Sale', 'sunnytree'))
                    ),
                    el(
                        'div',
                        { className: 'sunny-product-img' },
                        imageUrl
                            ? el('img', { src: imageUrl, alt: product.name })
                            : el(Placeholder, { icon: 'format-image' })
                    ),
                    el(
                        'div',
                        { className: 'sunny-product-content' },
                        el('span', { className: 'sunny-product-title-cat' }, product.name),
                        el(
                            'div',
                            { className: 'sunny-flex-container' },
                            el(
                                'div',
                                { className: 'sunny-product-prices' },
                                el(
                                    'div',
                                    { className: 'sunny-price' },
                                    isOnSale && regularPrice && el(
                                        'p',
                                        { className: 'sunny-from-price' },
                                        product.currency_symbol + regularPrice
                                    ),
                                    el(
                                        'p',
                                        { className: 'sunny-current-price' },
                                        product.currency_symbol + currentPrice
                                    )
                                )
                            ),
                            el(
                                'span',
                                { className: 'sunny-product-arrow' },
                                el(
                                    'svg',
                                    { width: '20', height: '17', viewBox: '0 0 20 17', fill: 'none' },
                                    el('path', {
                                        d: 'M1 9.42589H16.586L11.222 14.7899C10.8315 15.1804 10.8315 15.8136 11.2218 16.2039C11.6123 16.5944 12.2457 16.5944 12.6362 16.2039L19.707 9.13289C19.7535 9.08639 19.795 9.03514 19.8315 8.98064C19.8482 8.95539 19.86 8.92814 19.8745 8.90189C19.891 8.87089 19.91 8.84139 19.9233 8.80864C19.9375 8.77489 19.9455 8.73989 19.9555 8.70489C19.9637 8.67714 19.9745 8.65064 19.9802 8.62214C19.9932 8.55714 20 8.49164 20 8.42589C20 8.42514 19.9998 8.42439 19.9998 8.42364C19.9995 8.35889 19.993 8.29389 19.9802 8.23014C19.9742 8.20014 19.963 8.17239 19.9543 8.14289C19.9445 8.10964 19.937 8.07589 19.9235 8.04364C19.909 8.00889 19.8895 7.97739 19.8715 7.94464C19.858 7.92014 19.8472 7.89514 19.8318 7.87164C19.7952 7.81639 19.7532 7.76489 19.7065 7.71814L12.636 0.647887C12.2455 0.257387 11.6123 0.257387 11.2218 0.647637C10.8313 1.03814 10.8313 1.67139 11.2218 2.06214L16.5858 7.42589H1C0.44775 7.42589 0 7.87364 0 8.42589C0 8.97814 0.44775 9.42589 1 9.42589Z',
                                        fill: 'currentColor'
                                    })
                                )
                            )
                        )
                    )
                );
            }

            return el(
                Fragment,
                null,

                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: __('Display Settings', 'sunnytree'), initialOpen: true },

                        el(SelectControl, {
                            label: __('Display Type', 'sunnytree'),
                            value: displayType,
                            options: [
                                { label: __('Selected Products', 'sunnytree'), value: 'selected' },
                                { label: __('By Category', 'sunnytree'), value: 'category' },
                                { label: __('Featured Products', 'sunnytree'), value: 'featured' },
                                { label: __('On Sale Products', 'sunnytree'), value: 'on_sale' },
                                { label: __('Latest Products', 'sunnytree'), value: 'latest' },
                            ],
                            onChange: function (value) {
                                setAttributes({ displayType: value });
                            },
                        }),

                        displayType === 'selected' && el(FormTokenField, {
                            label: __('Select Products', 'sunnytree'),
                            value: selectedProductNames,
                            suggestions: productNames,
                            onChange: onProductsChange,
                            placeholder: __('Search products...', 'sunnytree'),
                        }),

                        displayType === 'category' && el(SelectControl, {
                            label: __('Category', 'sunnytree'),
                            value: category,
                            options: [{ label: __('Select a category', 'sunnytree'), value: '' }].concat(categories),
                            onChange: function (value) {
                                setAttributes({ category: value });
                            },
                        }),

                        displayType !== 'selected' && el(RangeControl, {
                            label: __('Number of Products', 'sunnytree'),
                            value: limit,
                            onChange: function (value) {
                                setAttributes({ limit: value });
                            },
                            min: 1,
                            max: 24,
                        }),

                        el(RangeControl, {
                            label: __('Columns', 'sunnytree'),
                            value: columns,
                            onChange: function (value) {
                                setAttributes({ columns: value });
                            },
                            min: 2,
                            max: 6,
                        })
                    ),

                    el(
                        PanelBody,
                        { title: __('Ordering', 'sunnytree'), initialOpen: false },

                        el(SelectControl, {
                            label: __('Order By', 'sunnytree'),
                            value: orderBy,
                            options: [
                                { label: __('Date', 'sunnytree'), value: 'date' },
                                { label: __('Title', 'sunnytree'), value: 'title' },
                                { label: __('Price', 'sunnytree'), value: 'price' },
                                { label: __('Popularity', 'sunnytree'), value: 'popularity' },
                                { label: __('Rating', 'sunnytree'), value: 'rating' },
                                { label: __('Menu Order', 'sunnytree'), value: 'menu_order' },
                            ],
                            onChange: function (value) {
                                setAttributes({ orderBy: value });
                            },
                        }),

                        el(SelectControl, {
                            label: __('Order', 'sunnytree'),
                            value: order,
                            options: [
                                { label: __('Descending', 'sunnytree'), value: 'DESC' },
                                { label: __('Ascending', 'sunnytree'), value: 'ASC' },
                            ],
                            onChange: function (value) {
                                setAttributes({ order: value });
                            },
                        })
                    )
                ),

                // Editor Preview
                el(
                    'div',
                    blockProps,
                    isLoading
                        ? el(
                              'div',
                              { className: 'sunnytree-product-grid__loading' },
                              el(Spinner, null),
                              el('p', null, __('Loading products...', 'sunnytree'))
                          )
                        : products.length === 0
                        ? el(
                              Placeholder,
                              {
                                  icon: 'products',
                                  label: __('Product Grid', 'sunnytree'),
                                  instructions: displayType === 'selected'
                                      ? __('Select products from the sidebar to display them here.', 'sunnytree')
                                      : __('No products found matching your criteria.', 'sunnytree'),
                              }
                          )
                        : el(
                              'div',
                              { className: 'sunnytree-product-grid__container' },
                              products.map(createProductCard)
                          )
                )
            );
        },

        save: function () {
            return null;
        },
    });
})(window.wp);

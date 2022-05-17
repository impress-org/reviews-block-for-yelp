/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import {registerBlockType} from '@wordpress/blocks';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';

import YelpBlock from './components/YelpBlock';
import domReady from '@wordpress/dom-ready';
import {render} from '@wordpress/element';
import {__} from '@wordpress/i18n';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType('yelp-block/profile', {
    title: __('Reviews Block for Yelp', 'yelp-widget-pro'),
    /**
     * @see ./edit.js
     */
    edit: Edit,

    /**
     * @see ./save.js
     */
    save: () => {
        return null;
    },
});

domReady(function () {
    // Don't run when Gutenberg / Block editor is active.
    if (document.body.classList.contains('block-editor-page')) {
        return;
    }

    const reviewBlocks = document.querySelectorAll('.root-yelp-block');

    reviewBlocks.forEach((reviewBlock) => {
        const attributes = reviewBlock.dataset;
        render(<YelpBlock attributes={attributes} />, reviewBlock);
    });
});

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')

// any css you import will output into a single css file
import './styles/app.css';

// start the stimulus application
import './bootstrap';

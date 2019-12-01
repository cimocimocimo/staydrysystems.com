// Theme by default loads a jQuery as dependency of the main script.
// Let's include it using ES6 modules import.
import $ from 'jquery'

// Import everything from autoload
// import "./autoload/**/*"

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import faq from './routes/faq';

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Home page
  home,
  // About Us page, note the change from about-us to aboutUs.
  // aboutUs,
  // single,
  faq,
});

// Load Events
$(document).ready(() => routes.loadEvents());

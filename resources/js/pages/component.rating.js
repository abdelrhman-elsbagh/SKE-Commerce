/*
* Theme: Attex - Responsive Bootstrap 5 SKE-Commerce Dashboard
* Author: Coderthemes
* Component: Ratings
*/

import 'jquery.rateit/scripts/jquery.rateit';

// Rated
$("#js-interaction").bind('rated', function (event, value) {
  $('#jsvalue').text('You\'ve rated it: ' + value);
});

// Reset
$("#js-interaction").bind('reset', function () {
  $('#jsvalue').text('Rating reset');
});

// Over
$("#js-interaction").bind('over', function (event, value) {
  $('#jshover').text('Hovering over: ' + value);
});

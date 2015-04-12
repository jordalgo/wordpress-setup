let $ = require('jQuery');

import mod1 from './mod1-sample';

$(document).ready(function(){
  mod1();
  $('.mobile-hamburger-menu').click(() => {
    $('.access, .mobile-hamburger-menu').toggleClass('active');
  });
});


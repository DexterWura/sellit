'use strict';

$( document ).ready(function() {
  //preloader
  $(".preloader").delay(300).animate({
    "opacity" : "0"
    }, 300, function() {
    $(".preloader").css("display","none");
  });
});

// mobile menu js
$(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on("click", function() {
  const element = $(this).parent("li");
  if (element.hasClass("open")) {
    element.removeClass("open");
    element.find("li").removeClass("open");
  }
  else {
    element.addClass("open");
    element.siblings("li").removeClass("open");
    element.siblings("li").find("li").removeClass("open");
  }
});


$('.header__search-btn').on('click', function(){
  $(this).toggleClass('active');
  $('.header-search-form').toggleClass('active');
});

$(document).on('click touchstart', function (e){
  if (!$(e.target).is('.header__search-btn, .header__search-btn *, .header-search-form, .header-search-form *')) {
    $('.header-search-form').removeClass('active');
    $('.header__search-btn').removeClass('active');
  }
});


// main wrapper calculator
var bodySelector = document.querySelector('body');
var header = document.querySelector('.header');
var innerHero = document.querySelector('.inner-hero');
var footer = document.querySelector('.footer');
var sectionBg = document.querySelector('.main-wrapper > div.section--bg, .main-wrapper > section.section--bg');

(function(){
  if(bodySelector.contains(header) && bodySelector.contains(innerHero) && bodySelector.contains(footer) && bodySelector.contains(sectionBg)){
    var headerHeight = document.querySelector('.header').clientHeight;
    var innerHeroHeight = document.querySelector('.inner-hero').clientHeight;
    var footerHeight = document.querySelector('.footer').clientHeight;

    var totalHeight = parseInt( headerHeight, 10 ) + parseInt( innerHeroHeight, 10 ) + parseInt( footerHeight, 10 ) + 'px'; 
   
    var minHeight = '100vh';
    document.querySelector('.main-wrapper').style.minHeight = `calc(${minHeight} - ${totalHeight})`;
    document.querySelector('.main-wrapper > div.section--bg, .main-wrapper > section.section--bg').style.minHeight = `calc(${minHeight} - ${totalHeight})`;
  }
})();

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

// Show or hide the sticky footer button
$(window).on("scroll", function() {
	if ($(this).scrollTop() > 200) {
			$(".scroll-to-top").fadeIn(200);
	} else {
			$(".scroll-to-top").fadeOut(200);
	}
});

// Animate the scroll to top
$(".scroll-to-top").on("click", function(event) {
	event.preventDefault();
	$("html, body").animate({scrollTop: 0}, 300);
});

new WOW().init();

// faq js
$('.faq-single__header').each(function(){
  $(this).on('click', function(){
    $(this).siblings('.faq-single__content').slideToggle();
    $(this).parent('.faq-single').toggleClass('active');
  });
});

// with short level
$('[data-countdown]').each(function() {
  var $this = $(this), finalDate = $(this).data('countdown');
  $this.countdown(finalDate).on('update.countdown', function(event) {
    var format = '%D days %H hr';
    $(this).html(event.strftime(format));
  }).on('finish.countdown', function(event) {
    var expireData = $(this).data('title');
    $(this).html(expireData).addClass('disabled');
  });
});

// brand-slider js 
$('.brand-slider').slick({
  infinite: true,
  slidesToShow: 5,
  slidesToScroll: 1,
  dots: false,
  arrows: false,
  autoplay: true,
  cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
  speed: 1000,
  autoplaySpeed: 1000,
  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 4,
      }
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 3,
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 2,
      }
    }
  ]
});

// testimonial-slider js 
$('.testimonial-slider').slick({
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  prevArrow: '<div class="prev"><i class="las la-angle-left"></i></div>',
  nextArrow: '<div class="next"><i class="las la-angle-right"></i></div>',
  autoplay: true,
  cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
  speed: 1000,
  autoplaySpeed: 1000,
  responsive: [
    {
      breakpoint: 576,
      settings: {
        dots: true,
        arrows: false,
      }
    }
  ]
});
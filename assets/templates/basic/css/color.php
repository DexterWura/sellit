<?php
header("Content-Type:text/css");
$color = "#f0f";
function checkhexcolor($color) {
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) AND $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color OR !checkhexcolor($color)) {
    $color = "#336699";
}

function checkhexcolor2($secondColor) {
    return preg_match('/^#[a-f0-9]{6}$/i', $secondColor);
}

?>

.btn--base,.header-search-form__btn,.btn-outline--base:hover,.domain-list__header,.faq-single.active .faq-single__header,.read-more-btn:hover,.scroll-to-top,.social-link-list li a,.post-share li a:hover,.custom--radio label::after,.pagination .page-item.active .page-link,.pagination .page-item .page-link:hover,.buying-advice li::before,.custom--file-upload::before,.profile-thumb .avatar-edit label,.input-group-text:not(.style--two),.testimonial-slider .slick-dots li.slick-active button,.price-slider .ui-widget-header,.action-sidebar-open,.action-sidebar-close, .qr-code-copy-form .text-copy-btn, .bg--base, .qr-code-form__btn{
    background-color: <?php echo $color ?> !important;
}

.header .main-menu li a:hover, .header .main-menu li a:focus,.domain-list__list .domain-name,.blog-item__title a:hover,.inline-menu li a:hover,.text--base,.contact-card__icon,.s-post__title a:hover,.custom--checkbox input:checked~label::before,.account-section .custom-icon-field .form--control:focus~i,.forget-pass,.policy-link-page,.title a:hover, .header .main-menu li.menu_has_children:hover>a::before{
    color: <?php echo $color ?> !important;
}
.btn-outline--base,.read-more-btn{
    color: <?php echo $color ?> !important;
    border: 1px solid <?php echo $color ?> !important;
}
.post-share li a:hover,.price-slider .ui-slider .ui-slider-handle{
    border: 1px solid <?php echo $color ?> !important;
}
.custom--radio input[type=radio]:checked~label::before,.form--control:focus,.pagination .page-item.active .page-link,.pagination .page-item .page-link:hover,.form-control:focus{
    border-color: <?php echo $color ?> !important;
}
.buying-advice::before{
    border-left: 1px dashed <?php echo $color ?> !important;
}
.form-check-input:checked{
    background-color: <?php echo $color ?> !important;
    border-color: <?php echo $color ?> !important;
}
.top--border-base,.add-money-card{
    border-top: 2px solid <?php echo $color ?> !important;
}
.add-moeny-card-middle{
    background-color: <?php echo $color ?>11 !important;
}


.offer-widget__form .form--control:focus ~ .currency-text {
    border-color: <?php echo $color ?> !important;
}
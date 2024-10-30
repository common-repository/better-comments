jQuery(document).ready(function () {

  const upgrade_url = BCO.upgrade_url;
  const plus_feature = BCO.plus_feature;
  const pro_feature = BCO.pro_feature;
  const business_feature = BCO.business_feature;

  jQuery('ul.cf-container__tabs-list li:nth-child(1)').addClass('tab-form').prepend('  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.625em" height="1.625em" style="transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 36 36"><path class="fill-navy" d="M21 12H7a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1zM8 10h12V7.94H8z" class="clr-i-outline clr-i-outline-path-1" fill="#626262"/><path class="fill-navy" d="M21 14.08H7a1 1 0 0 0-1 1V19a1 1 0 0 0 1 1h11.36L22 16.3v-1.22a1 1 0 0 0-1-1zM20 18H8v-2h12z" class="clr-i-outline clr-i-outline-path-2" fill="#626262"/><path class="fill-navy" d="M11.06 31.51v-.06l.32-1.39H4V4h20v10.25l2-1.89V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v28a1 1 0 0 0 1 1h8a3.44 3.44 0 0 1 .06-.49z" class="clr-i-outline clr-i-outline-path-3" fill="#626262"/><path d="M22 19.17l-.78.79a1 1 0 0 0 .78-.79z" class="clr-i-outline clr-i-outline-path-4" fill="#626262"/><path d="M6 26.94a1 1 0 0 0 1 1h4.84l.3-1.3l.13-.55v-.05H8V24h6.34l2-2H7a1 1 0 0 0-1 1z" class="clr-i-outline clr-i-outline-path-5" fill="#626262"/><path class="fill-blue" d="M33.49 16.67l-3.37-3.37a1.61 1.61 0 0 0-2.28 0L14.13 27.09L13 31.9a1.61 1.61 0 0 0 1.26 1.9a1.55 1.55 0 0 0 .31 0a1.15 1.15 0 0 0 .37 0l4.85-1.07L33.49 19a1.6 1.6 0 0 0 0-2.27zM18.77 30.91l-3.66.81l.89-3.63L26.28 17.7l2.82 2.82zm11.46-11.52l-2.82-2.82L29 15l2.84 2.84z" class="clr-i-outline clr-i-outline-path-6" fill="#626262"/><rect x="0" y="0" width="36" height="36" fill="rgba(0, 0, 0, 0)" /></svg>');

  jQuery('ul.cf-container__tabs-list li:nth-child(2)').prepend('<svg xmlns="http://www.w3.org/2000/svg" width="1.625em" height="1.625em" style="transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 36 36"><path class="fill-purple" d="M31 10V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h23a2 2 0 0 0 2-2zM6 4h23v6H6z" class="clr-i-outline clr-i-outline-path-1" fill="#626262"/><path class="fill-pink" d="M33 6h-1v6.29l-13.3 4.25a1 1 0 0 0-.7 1V19h-2v14a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V19h-2v-.73L33.3 14a1 1 0 0 0 .7-1V7a1 1 0 0 0-1-1zM20 33h-2V21h2z" class="clr-i-outline clr-i-outline-path-2" fill="#626262"/><rect x="0" y="0" width="36" height="36" fill="rgba(0, 0, 0, 0)" /></svg>').attr('id', 'tab-customizer').attr('id', 'tab-customizer');

  jQuery('ul.cf-container__tabs-list li:nth-child(3)').prepend('<svg xmlns="http://www.w3.org/2000/svg" width="1.625em" height="1.625em" style=" transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16"><g fill="#626262"><path fill-rule="evenodd" d="M0 1l1-1l3.081 2.2a1 1 0 0 1 .419.815v.07a1 1 0 0 0 .293.708L10.5 9.5l.914-.305a1 1 0 0 1 1.023.242l3.356 3.356a1 1 0 0 1 0 1.414l-1.586 1.586a1 1 0 0 1-1.414 0l-3.356-3.356a1 1 0 0 1-.242-1.023L9.5 10.5L3.793 4.793a1 1 0 0 0-.707-.293h-.071a1 1 0 0 1-.814-.419L0 1zm11.354 9.646a.5.5 0 0 0-.708.708l3 3a.5.5 0 0 0 .708-.708l-3-3z"/><path fill-rule="evenodd" class="fill-blue" d="M15.898 2.223a3.003 3.003 0 0 1-3.679 3.674L5.878 12.15a3 3 0 1 1-2.027-2.027l6.252-6.341A3 3 0 0 1 13.778.1l-2.142 2.142L12 4l1.757.364l2.141-2.141zm-13.37 9.019L3.001 11l.471.242l.529.026l.287.445l.445.287l.026.529L5 13l-.242.471l-.026.529l-.445.287l-.287.445l-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471l.026-.529l.445-.287l.287-.445l.529-.026z"/></g><rect x="0" y="0" width="16" height="16" fill="rgba(0, 0, 0, 0)" /></svg>').attr('id', 'tab-tweaks');
  // Quicktags
  jQuery('ul.cf-container__tabs-list li:nth-child(6)').addClass('tab-quicktags').prepend('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.625em" height="1.625em" style="transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path class="fill-fuchsia" d="M14.594 4l-.313.281l-11 11l-.687.719l.687.719l9 9l.719.687l.719-.687l11-11l.281-.313V4zm.844 2H23v7.563l-10 10L5.437 16zM26 7v2h1v8.156l-9.5 9.438l-1.25-1.25l-1.406 1.406l1.937 1.969l.719.687l.688-.687l10.53-10.407L29 18V7zm-6 1c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1z" fill="#626262"/><rect x="0" y="0" width="32" height="32" fill="rgba(0, 0, 0, 0)" /></svg>');
  // SEO
  jQuery('ul.cf-container__tabs-list li:nth-child(4)').addClass('tab-seo').prepend('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.625em" height="1.625em" style="transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path class="fill-olive" d="M512 310.829v-73.154c-3.616-38.626-31.154-38.29-73.143-36.576v146.306c67.411 7.006 70.837-19.505 73.143-36.576zM73.143 347.405V201.1c-41.99-1.714-69.527-2.05-73.143 36.576v73.154c2.306 17.07 5.732 43.582 73.143 36.576zm-54.857 91.442h475.428V512H18.286v-73.153zM328.32 73.08c-11.526-94.655-130.877-100.188-144.64 0h144.64zM21.482 32.86c9.852-18.592 36.27-19.676 47.438-1.947c9.628 15.282 1.753 34.795-14.068 40.43l.005 111.467H36.571V71.394C21.558 66.182 13.321 48.26 21.482 32.86zm325.947 195.67c0 21.04-22.93 34.26-41.174 23.74c-18.245-10.519-18.245-36.96 0-47.48s41.174 2.7 41.174 23.74zm-169.174 23.74c18.244 10.52 41.174-2.7 41.174-23.74s-22.93-34.26-41.174-23.74c-18.245 10.52-18.245 36.961 0 47.48zm242.316-87.749V420.56H91.43V164.522c0-40.399 32.75-73.153 73.142-73.153H347.43c40.393 0 73.142 32.754 73.142 73.153zM169.091 268.1c30.408 17.532 68.623-4.502 68.623-39.568c0-35.065-38.215-57.1-68.623-39.567s-30.407 61.602 0 79.135zm178.338 61.018H164.57v36.577H347.43v-36.577zm18.285-100.586c0-35.065-38.215-57.1-68.623-39.567s-30.407 61.602 0 79.135c30.408 17.532 68.623-4.502 68.623-39.568z" fill="#626262"/><rect x="0" y="0" width="512" height="512" fill="rgba(0, 0, 0, 0)" /></svg>');

  // disable
  jQuery('ul.cf-container__tabs-list li:nth-child(5)').addClass('tab-disable').prepend('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.625em" height="1.625em" style="transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path class="fill-orange" d="M18.521 1.478a1 1 0 0 0-1.414 0L1.48 17.107a1 1 0 1 0 1.414 1.414L18.52 2.892a1 1 0 0 0 0-1.414zM3.108 13.498l2.56-2.56A4.18 4.18 0 0 1 5.555 10c0-2.379 1.99-4.309 4.445-4.309c.286 0 .564.032.835.082l1.203-1.202A12.645 12.645 0 0 0 10 4.401C3.44 4.4 0 9.231 0 10c0 .423 1.057 2.09 3.108 3.497zm13.787-6.993l-2.562 2.56c.069.302.111.613.111.935c0 2.379-1.989 4.307-4.444 4.307c-.284 0-.56-.032-.829-.081l-1.204 1.203c.642.104 1.316.17 2.033.17c6.56 0 10-4.833 10-5.599c0-.424-1.056-2.09-3.105-3.495z" fill="#626262"/><rect x="0" y="0" width="20" height="20" fill="rgba(0, 0, 0, 0)" /></svg>');

  // security 
  jQuery('ul.cf-container__tabs-list li:nth-child(70)').addClass('tab-secuirty').prepend('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.625em" height="1.625em" style="transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path class="fill-olive" d="M14 16.59L11.41 14L10 15.41l4 4l8-8L20.59 10L14 16.59z" fill="#626262"/><path d="M16 30l-6.176-3.293A10.982 10.982 0 0 1 4 17V4a2.002 2.002 0 0 1 2-2h20a2.002 2.002 0 0 1 2 2v13a10.982 10.982 0 0 1-5.824 9.707zM6 4v13a8.985 8.985 0 0 0 4.766 7.942L16 27.733l5.234-2.79A8.985 8.985 0 0 0 26 17V4z" fill="#626262"/><rect x="0" y="0" width="32" height="32" fill="rgba(0, 0, 0, 0)" /></svg>');

  // plugins
  jQuery('ul.cf-container__tabs-list li:nth-child(7)').prepend('<svg xmlns="http://www.w3.org/2000/svg" width="1.625em" height="1.625em" style="transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 128 128"><path d="M27.9 36.5H20V7.9c0-2.2 1.8-4 4-4s4 1.8 4 4l-.1 28.6z" fill="#e0e0e0"/><path d="M52 36.5h-7.9V7.9c0-2.2 1.8-4 4-4s4 1.8 4 4L52 36.5z" fill="#e0e0e0"/><path class="fill-fuchsia" d="M123.9 64.1c0 1-.8 1.8-1.8 1.8H98.5c-8.6 0-15.5 6.9-15.5 15.5l.2 16.4c0 14.9-11.6 26.5-26.2 26.3c-14.4-.2-25.8-12.4-25.8-26.8v-33c0-2.5 1.9-4.8 4.4-5c2.9-.3 5.3 2 5.3 4.8v33.2c0 9 6.9 16.7 15.9 17c9.4.4 16.8-7 16.8-16.6l-.2-16.4c0-13.9 11.3-25.2 25.2-25.2h23.7c1 0 1.8.8 1.8 1.8v6.2h-.2z" fill="#424242"/><path d="M121.9 58.2v5.7H98.5c-9.7 0-17.5 7.8-17.5 17.5l.2 16.4c0 13.6-10.4 24.3-23.8 24.3H57c-13.1-.2-23.8-11.3-23.8-24.8v-33c0-1.6 1.1-2.9 2.5-3h.3c1.6 0 2.8 1.3 2.8 2.8v33.2c0 10.2 7.8 18.6 17.8 19h.8c10.2 0 18.1-8.2 18.1-18.6l-.2-16.4c0-12.8 10.4-23.2 23.2-23.2l23.4.1m.3-2H98.5c-13.9 0-25.2 11.3-25.2 25.2l.2 16.4c0 9.3-7.1 16.6-16.1 16.6h-.7c-9-.4-15.9-8.1-15.9-17V64.1c0-2.7-2.2-4.8-4.8-4.8h-.5c-2.5.2-4.4 2.5-4.4 5v32.9c0 14.4 11.4 26.5 25.8 26.8h.5c14.5 0 25.8-11.5 25.8-26.3L83 81.3c0-8.6 6.9-15.5 15.5-15.5h23.7c1 0 1.8-.8 1.8-1.8v-6.2c-.1-.9-.9-1.6-1.8-1.6z" fill="#eee" opacity=".2"/><path d="M24 5.9c1.1 0 2 .9 2 2v26.6h-4V7.9c0-1.1.9-2 2-2m0-2c-2.2 0-4 1.8-4 4v28.6h7.9V7.9c0-2.2-1.8-4-3.9-4z" fill="#424242" opacity=".2"/><path d="M48.1 5.9c1.1 0 2 .9 2 2v26.6h-3.9V7.9c-.1-1.1.8-2 1.9-2m0-2c-2.2 0-4 1.8-4 4v28.6H52V7.9c0-2.2-1.7-4-3.9-4z" fill="#424242" opacity=".2"/><path d="M61.9 24H10c-3.3 0-6 2.5-6 5.9c0 3.3 2.7 6.1 6 6.1h2v19.3c0 5.3 3.6 12.2 8 15.1c19.3 13.2 40-.3 40-18.6V36h1.9c3.3 0 6-2.8 6-6.2v.1c0-3.3-2.7-5.9-6-5.9z" fill="#424242"/><path d="M61.9 27c1.7 0 3.1 1.3 3.1 2.9c0 1.7-1.5 3.1-3.1 3.1H60c-1.7 0-3 1.3-3 3v15.8c0 11.5-9.4 20.9-21 20.9c-4.8 0-9.8-1.7-14.3-4.8c-3.6-2.4-6.7-8.4-6.7-12.7V36c0-1.7-1.3-3-3-3h-2c-1.7 0-3-1.4-3-3.1c0-1.6 1.3-2.9 3-2.9h51.9m0-3H10c-3.3 0-6 2.5-6 5.9c0 3.3 2.7 6.1 6 6.1h2v19.3c0 5.3 3.6 12.2 8 15.1c5.4 3.7 10.9 5.3 16 5.3c13.2 0 24-10.7 24-23.9V36h1.9c3.3 0 6.1-2.8 6.1-6.1S65.2 24 61.9 24zm6.1 5.8c0 .1 0 0 0 0z" fill="#eee" opacity=".2"/><rect x="0" y="0" width="128" height="128" fill="rgba(0, 0, 0, 0)" /></svg>').attr('id', 'tab-plugins');

  // Free: upgrade all
  jQuery('.free .plus input, .free .pro input, .free .business input').attr('disabled', 'disabled').addClass('upgrade');
  // Plus: ugrade to Pro or Business
  jQuery('.plan-plus .pro input, .plan-plus .business input').attr('disabled', 'disabled').addClass('upgrade');
  // Pro: ugrade to Business
  jQuery('.plan-pro .business input').attr('disabled', 'disabled').addClass('upgrade');

  jQuery('.free .cf-image.plus *, .free textarea.plus, .free .cf-color.plus *').attr('disabled', 'disabled');
  jQuery('.free .cf-image.pro *, .free textarea.pro, .free .cf-color.pro *').attr('disabled', 'disabled');
  jQuery('.free .cf-image.business *, .free textarea.business, .free .cf-color.business *').attr('disabled', 'disabled');

  /* On montre le message à ceux qui n'ont pas le plan idoine */
  jQuery('.free .upselly, .plan-plus .upselly.pro, .plan-plus .upselly.business, .plan-pro .upselly.business').show();

  setTimeout(function () {
    jQuery('input:disabled, select:disabled').each(function () {
      jQuery(this).prop('checked', false);
    })
  }, 2000);

  window.setInterval(function () {
    jQuery('ul.cf-container__tabs-list li:nth-child(1)').addClass('tab-form');
    jQuery('ul.cf-container__tabs-list li:nth-child(2)').addClass('tab-design');
    jQuery('ul.cf-container__tabs-list li:nth-child(3)').addClass('tab-admin');
    jQuery('ul.cf-container__tabs-list li:nth-child(4)').addClass('tab-quicktags');
    jQuery('ul.cf-container__tabs-list li:nth-child(5)').addClass('tab-security');
    jQuery('ul.cf-container__tabs-list li:nth-child(6)').addClass('tab-seo');
    jQuery('ul.cf-container__tabs-list li:nth-child(7)').addClass('tab-disable');

    /* add class to last visible screen */
    jQuery('.cf-container__fields:visible:last').addClass('last-visible-div');

    /* backticks to code tags */
    const labels = document.querySelectorAll('label');
    for (let label of labels) {
      html = label.innerHTML.replace(/`(.*?)`/g, '<code>$1</code>')
      label.innerHTML = html
    }

  }, 1000);
});

// Json parser
jQuery.getJSON(BetterComments.utopiqueJson).done(function (json) {
  for (var counter = 0; counter < json.length; counter++) {
    jQuery('#plugins-grid').append("<div class='plugins-block'><div class='plugins-image'><a href='" + json[counter].url + "' rel='noopener' target='_blank'><img src='" + json[counter].icon + "' class='alignleft plugins'></div><div class='plugins-body'><strong>" + json[counter].title + "</strong></a> " + json[counter].body + "</div><div class='plugins-install'><a href='" + BetterComments.installUrl + "' rel='noopener' target='_blank'>" + BetterComments.installText + "</a></div></div>");
  }
});

document.addEventListener('DOMContentLoaded', function() {
  setInterval(function() {
      var publishButton = document.getElementById('publish');
      if (publishButton) {
          publishButton.removeAttribute('disabled');
      }
  }, 2000); // Delay in milliseconds (2000ms = 2s)
});


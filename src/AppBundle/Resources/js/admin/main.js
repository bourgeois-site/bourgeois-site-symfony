var transliterate = function(word) {
  var a = {"а":"a", "б":"b", "в":"v", "г":"g", "д":"d", "е":"ye", "ё":"yo",
    "ж":"zh", "з":"z", "и":"i", "й":"y", "к":"k", "л":"l", "м":"m", "н":"n",
    "о":"o", "п":"p", "р":"r", "с":"s", "т":"t", "у":"u", "ф":"f", "х":"h",
    "ц":"ts", "ч":"ch", "ш":"sh", "щ":"shch", "ы":"i", "ъ":"_", "ь":"_",
    "э":"e", "ю":"yu", "я":"ya"}

  return word.split('').map(function(char) {
    return a[char] || char;
  }).join("");
}
    
var setActiveLink = function() {
  var currentUrl = decodeURI(window.location.pathname);
  // 7 below is a length of '/админ/'
  var linkName = transliterate(
    currentUrl.substring(7).replace(/\/(.)*/, "")
  ) + "_link";

  $('aside #'.concat(linkName)).addClass("active");
}

$(document).ready(function() {
  $('[title]').tooltip({ placement: 'bottom'});

  $('a[data-confirm]').on('click', function(e) {
    var confirmation = confirm($(this).data('confirm'));
    if (confirmation != true) { e.preventDefault(); }
  });

  setActiveLink();

  $('.archive_link').on('click', function() {
    $(this).parents('.list-group-item').hide(100);
  });

  $('.restore_link').on('click', function() {
    $(this).parents('.list-group-item').hide(100);
  });

  // autohide social network 
  if ($('#internet_contact_isEmail option[value="1"]').attr('selected') == "selected") {
    $('#internet_contact_socialName').parent().css('display', 'none');
  }

  $('#internet_contact_isEmail option[value="0"]').on('click', function() {
    $('#internet_contact_socialName').parent().slideDown(300);
  });

  $('#internet_contact_isEmail option[value="1"]').on('click', function() {
    $('#internet_contact_socialName').parent().slideUp(300);
  });

  $('#global_submit').on('click', function() {
      $('form').submit();
  });
});

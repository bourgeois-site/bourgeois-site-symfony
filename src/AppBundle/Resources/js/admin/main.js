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

  setActiveLink();
  setTimeout(function() {
    $('div.alert').hide(300);
  }, 5000);

  $('.archive_link').on('click', function() {
    $(this).parents('.list-group-item').hide(100);
  });

  $('.restore_link').on('click', function() {
    $(this).parents('.list-group-item').hide(100);
  });
});

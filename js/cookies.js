function handleCookies(cookieBanner, acceptCookies) {
    function setCookie(name, value, days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      var expires = "expires=" + date.toUTCString();
      document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }
  
    function getCookie(name) {
      var cookieName = name + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var cookies = decodedCookie.split(';');
      for (var i = 0; i < cookies.length; i++) {
          var cookie = cookies[i];
          while (cookie.charAt(0) == ' ') {
              cookie = cookie.substring(1);
          }
          if (cookie.indexOf(cookieName) == 0) {
              return cookie.substring(cookieName.length, cookie.length);
          }
      }
      return "";
    }
  
    var expirationDays = 30; // Número de dias até a expiração dos cookies
  
    var cookiesAccepted = getCookie("cookiesAccepted") ?? "";
    var lastAccepted = getCookie("lastAccepted");
  
    if (!cookiesAccepted || (lastAccepted && new Date() > new Date(lastAccepted).setDate(new Date(lastAccepted).getDate() + expirationDays))) {
        cookieBanner.classList.add("show"); // Adiciona uma classe temporária ao banner
    }
  
    acceptCookies.addEventListener("click", function() {
        setCookie("cookiesAccepted", "true", expirationDays);
        setCookie("lastAccepted", new Date().toISOString(), expirationDays);
        cookieBanner.style.display = "none";
    });
  }
  
  var cookieBanner = document.getElementById("cookie-banner");
  var acceptCookies = document.getElementById("accept-cookies");
  
  if (cookieBanner && acceptCookies) {
    handleCookies(cookieBanner, acceptCookies);
  }
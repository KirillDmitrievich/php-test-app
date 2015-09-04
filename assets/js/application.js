function addOrUpdateUrlParam(name, value){
  var href = window.location.href;
  var regex = new RegExp("[&\\?]" + name + "=");
  if(regex.test(href)) {
    regex = new RegExp("([&\\?])" + name + "=\\d*\\w*");
    window.location.href = href.replace(regex, "$1" + name + "=" + value);
  }else{
    if(href.indexOf("?") > -1){
      window.location.href = href + "&" + name + "=" + value;
    }else{
      window.location.href = href + "?" + name + "=" + value;
    }
  }
}

document.addEventListener('DOMContentLoaded',function(){
  var p = document.querySelectorAll("[data-lang");
  for(var i=0; i<p.length; i++){
    p[i].onclick = function(){
      addOrUpdateUrlParam('lang', this.getAttribute('data-lang'));
    }
  }

  if (document.getElementById('reg')) {
    document.getElementById('reg').onclick = function () {
      var inputs = document.querySelectorAll("[validate]");
      var valid = true;


      while(error = document.getElementsByClassName('error__message')[0]){
        error.parentNode.removeChild(error);
      };

      function rise_error(elem, message){
        elem.parentNode.insertAdjacentHTML('beforeend','<div class="error__message">' + message + '</div>');
        valid = false;
      }

      for(var i=0; i<inputs.length; i++){
        switch(inputs[i].getAttribute('validate')){
          case 'email':
            if(inputs[i].value != '' && !/[\w\d]+@[\w\d]+\.\w+/.test(inputs[i].value)){
              rise_error(inputs[i], inputs[i].getAttribute('error-message'));
            }
            break
          case 'password':
            if(inputs[i].value != '' && inputs[i].value.length < 8){
              rise_error(inputs[i], inputs[i].getAttribute('error-message'));
            }
            break
          case 'password_confirmation':
            if(inputs[i].value != '' && inputs[i].value != document.getElementById('password').value){
              rise_error(inputs[i], inputs[i].getAttribute('error-message'));
            }
            break
          case 'phone':
            if(inputs[i].value != '' && !/\d{8,8}/.test(inputs[i].value)){
              rise_error(inputs[i], inputs[i].getAttribute('error-message'));
            }
            break
          default:
            break
        }
        if(inputs[i].getAttribute('required') && inputs[i].value == ''){
          rise_error(inputs[i], inputs[i].getAttribute('required-message'));
        }
      }
      return valid;
    }
  };
});
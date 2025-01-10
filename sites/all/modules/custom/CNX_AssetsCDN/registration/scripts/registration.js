function getSubSector() {
  const selectedSector = document.getElementById('comapny-sector-value').value;

  for (let key in sector_list) {
    let obj = sector_list[key];
    if (obj.id == selectedSector) {
      createSubSectorRadios(obj.children);
    }
  }
}

function createSubSectorRadios(sub_sectors) {
  let subsectorForm = document.getElementById("subsectorForm");
  //   while (subsectorForm.firstChild) {
  //     subsectorForm.removeChild(subsectorForm.firstChild);
  // }
  const subsectoredit = document.getElementById('subsector_edit').value;
  // console.log(subsectoredit);
  const templating = (arr) => {
    let result = `<option value=""></option>`;
    arr.forEach(obj => {
      var selected = '';
      if (obj.id == subsectoredit) {
        var selected = 'selected';
      }
      result += `
      <option value="${obj.id}" ${selected}>${obj.name}</option>
      `
    })
    subsectorForm.innerHTML = result;
  }
  templating(sub_sectors);
  return;
}

function correctMdbSelectCss() {
  let mdbSelectElems = document.querySelectorAll("input.select-input");
  for (let i = 0; i < mdbSelectElems.length; i++) {
    let sElem = mdbSelectElems[i];
    if (sElem instanceof HTMLInputElement) {
      if (!sElem.classList.contains("active")) sElem.classList.add("active");
    }
  }
}

function focusOnFirstInvalidElement() {
  let invalidElems = document.querySelectorAll(".form-control:invalid");
  if (invalidElems.item(0) instanceof HTMLElement) {
    invalidElems.item(0).focus();
  }
}

var onloadCallback = function () {
  let site_key = document.getElementById('recaptcha_site_key').value;
  grecaptcha.render('html_element', {
    'sitekey': site_key
  });
};
window.addEventListener("load", (event) => {
  var inputFields = document.querySelectorAll('input');
  inputFields.forEach(function (field) {
    field.setAttribute('autocomplete', 'nope');
  });

  async function validate(obj, event) {
    let exhibitor_email = {};
    let retVal = true;
      document.getElementById('create-submit').setAttribute('disabled', '');
      exhibitor_email.email = obj.ex_contactemail.value;
      let resp;
      const objAjax = new ajax("/newemail/check", exhibitor_email);

      objAjax.getResponse().then(response => {
        if (response.hasOwnProperty("status")) {
          // console.log(response);
          if (response.status > 0) {
            alert(response.message);
            document.getElementById('create-submit').removeAttribute('disabled');
            retVal = false;
          }else{
            if (!validateCaptcha(event)) {
              document.getElementById('create-submit').removeAttribute('disabled');
              retVal = false;
            }
          }
        }
      });

    return retVal;
  }

  function validateCaptcha(event) {
    let recaptcha_response = document.getElementById('g-recaptcha-response').value;
    let recaptcha_response_element = document.getElementById('g-recaptcha-response');
    recaptcha_response_element.setAttribute('data-type', 'image');
    if (recaptcha_response == '') {
      alert('Please tick the captcha to continue.');
      return false;
    } else {
      event.target.submit();
      return true;
    }
  }
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation');
  getSubSector();
  // Loop over them and prevent submission
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      document.getElementById('create-submit').setAttribute('disabled', '');
      if (!form.checkValidity()) {
        correctMdbSelectCss();
        focusOnFirstInvalidElement();
        form.classList.add('was-validated');
        document.getElementById('create-submit').removeAttribute('disabled');
        return false;
      }
      if (document.getElementById('registration_mode').value) {
        if (!validate(form, event)) {
          document.getElementById('create-submit').removeAttribute('disabled');
          return false;
        }
        else{

          return false;
        }

      }else{
        event.target.submit();
      }
    }, false)

  })
});


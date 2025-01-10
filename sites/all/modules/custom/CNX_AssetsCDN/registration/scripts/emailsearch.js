
function searchEmail() {
    let email = document.getElementById("primary_email");
    let error_div = document.getElementById("error_div");
    let error_message = document.getElementById("error_message");
    let create_accbtn = document.getElementById("create_accbtn");
    let sign_in_btn = document.getElementById("sign_in");
    if (email) {
        let searchButton = document.getElementById("search_result");
        let searchResultDiv = document.getElementById("search_result_div");
        let otpResultDiv = document.getElementById("otp_result_div");
        let compName = document.getElementById("comp_name");
        let custEmail = document.getElementById("cust_email");
        let vat_no = document.getElementById('vat_no');
        let resend_text = document.getElementById("resend_text");
        let resend_btn = document.getElementById("resend_btn");
        let timerDiv = document.getElementById("timer_div");
        create_accbtn.classList.add('d-none');
        sign_in_btn.classList.add('d-none');
        searchResultDiv.style.display = "block";
        error_div.style.display = "none";
        email.setAttribute("disabled", true);
        searchButton.setAttribute("disabled", true);
        otpResultDiv.style.display = "none";
        let exhibitor_email = {};
        exhibitor_email.email = email.value;
        if (exhibitor_email.email != '') {
            var regex = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,25})+$/;
            result = regex.test(exhibitor_email.email);

            if (result === false) {
                searchResultDiv.style.display = "none";
                email.removeAttribute("disabled", true);
                searchButton.removeAttribute("disabled", true);
                error_div.style.display = "block";
                searchButton.innerText = "Start Again";
                error_message.innerText = "Please enter valid email id.";
            } else {

                const aj = new ajax("/email/check", exhibitor_email);
                aj.getResponse().then(response => {
                    // console.log(response);
                    if (response.hasOwnProperty("status")) {
                        if (response.status > 0) {
                            if (response.hasOwnProperty("payload")) {
                                error_div.style.display = "none";
                                searchResultDiv.style.display = "none";
                                otpResultDiv.style.display = "block";
                                // checkbox3.classList.add("fa-check");
                                resend_text.style.display = "block";
                                error_div.style.display = "none";
                                resend_btn.style.display = "none";
                                // searchButton.removeAttribute("disabled", true);
                                error_div.style.display = "none";
                                custEmail.innerHTML = email.value;
                                let payload = response.payload;
                                var dateConvert = formatDateTimeFromSeconds(payload.expiry_duration);
                                const countdownExample = document.getElementById('countdown-element');
                                new Countdown(countdownExample, {
                                    countdown: dateConvert,
                                });

                                document.addEventListener('end.mdb.countdown', (e) => {
                                    var resend_btn = document.getElementById("resend_btn");
                                    var timerElement = document.getElementById("countdown-element");             
                                    timerDiv.style.display = 'none';
                                    resend_btn.style.display = 'block';
                                    resend_text.style.display = "none";

                                })
                                compName.innerText = payload.company_name;
                                if (payload.vat_no) {
                                    vat_no.innerText = payload.vat_no;
                                }

                            } else {
                                searchResultDiv.style.display = "none";
                                error_div.style.display = "block";
                                sign_in_btn.classList.remove('d-none');
                                error_message.innerText = "This email is already registered, we have sent you password reset link for you to reset your password & access."
                            }
                        } else {
                            // checkbox2.classList.remove("fa-check");
                            searchResultDiv.style.display = "none";
                            email.removeAttribute("disabled", true);
                            searchButton.removeAttribute("disabled", true);
                            error_div.style.display = "block";
                            searchButton.innerText = "Start Again";
                            create_accbtn.classList.remove('d-none');
                            error_message.innerText = "Your email not found, Please check email & try again or you can create new account!";
                        }
                    }
                });
            }
        } else {
            alert('Enter valid email id!');
            location.reload();
        }
    }

}

function verifyOtp() {
    let email = document.getElementById("primary_email");
    let otpResultDiv = document.getElementById("otp_result_div");
    // let checkbox4 = document.getElementById("checkbox4");
    // let checkbox5 = document.getElementById("checkbox5");
    let searchButton = document.getElementById("search_result");
    let searchResultDiv = document.getElementById("search_result_div");
    let otp_error_div = document.getElementById("error_div");
    let otp_error_message = document.getElementById("otp_error_message");
    let error_message = document.getElementById("error_message");
    otp_error_message.innerText = '';
    // let checkbox2 = document.getElementById("checkbox2");
    let otpDigit1 = document.getElementById('otp1').value;
    let otpDigit2 = document.getElementById('otp2').value;
    let otpDigit3 = document.getElementById('otp3').value;
    let otpDigit4 = document.getElementById('otp4').value;
    let company_div = document.getElementById('company_name_div');
    let company_name = document.getElementById('company_name_value');
    let vat_no = document.getElementById('vat_no_value');
    let finalOtp = otpDigit1 + otpDigit2 + otpDigit3 + otpDigit4;
    let create_accbtn = document.getElementById("create_accbtn");
    if (finalOtp.length == 4) {
        let exhibitor_data = {};
        exhibitor_data.email = email.value;
        exhibitor_data.enter_otp = finalOtp;
        email.setAttribute("disabled", true);
        searchButton.setAttribute("disabled", true);
        const OTPVerifyAjax = new ajax("/otp/verify", exhibitor_data);
        OTPVerifyAjax.getResponse().then(response => {

            if (response.hasOwnProperty("status")) {

                if (response.status > 0) {
                    payload = response.payload;
                    // checkbox4.classList.add("fa-check");
                    // checkbox5.classList.add("fa-check");
                    searchButton.setAttribute("disabled", true);
                    otpResultDiv.style.display = "none";
                    otp_error_div.style.display = "none";
                    create_accbtn.classList.add('d-none');
                    company_div.style.display = "block";
                    company_name.innerText = "Company: " + payload.company_name;
                    if (payload.vat_no) {
                        vat_no.innerText = "VAT No: " + payload.vat_no;
                    }
                } else {
                    // checkbox4.classList.remove("fa-check");
                    // checkbox5.classList.remove("fa-check");
                    searchResultDiv.style.display = "none";
                    email.setAttribute("disabled", true);
                    searchButton.setAttribute("disabled", true);
                    error_message.style.display = "none";
                    otp_error_div.style.display = "block";
                    create_accbtn.classList.add('d-none');
                    otp_error_message.innerText = response.message;
                }
            }
        });
    }

}

function moveToNextOrPrevious(currentInput, nextInputID, previousInputID) {
    let otpDigit1 = document.getElementById('otp1').value;
    let otpDigit2 = document.getElementById('otp2').value;
    let otpDigit3 = document.getElementById('otp3').value;
    let otpDigit4 = document.getElementById('otp4').value;
    let finalOtp = otpDigit1 + otpDigit2 + otpDigit3 + otpDigit4;
    if (finalOtp.length == 4) {
        verifyOtp();
    }
    var maxLength = parseInt(currentInput.getAttribute("maxlength"));
    let otp_error_message = document.getElementById("otp_error_message");
    otp_error_message.innerText = '';
    var currentLength = currentInput.value.length;

    currentInput.addEventListener('keydown', function (event) {
        if (event.key === 'Backspace' && currentLength === 0) {
            document.getElementById(previousInputID).focus();
        }
    });
    if (currentLength >= maxLength) {
        document.getElementById(nextInputID).focus();
    }
}


function rgisterExhibitor() {
    let checkBox = document.getElementById('checkboxNoLabel2');
    let email = document.getElementById("primary_email");
    let searchResultDiv = document.getElementById("search_result_div");
    let registrationBtn = document.getElementById('registration_now_btn');
    let exhibitor_email = {};
    exhibitor_email.email = email.value;
    if(!registrationBtn.classList.contains('button-clicked')) {
        registrationBtn.classList.add('button-clicked');
        registrationBtn.setAttribute('disabled','');
        if (checkBox.checked) {
            const preRegisterAjax = new ajax("/registration/preregister", exhibitor_email);
            preRegisterAjax.getResponse().then(response => {
                if (response.hasOwnProperty("status")) {
                    if (response.status > 0) {
                        window.location.href = window.location.origin + '/confirm/registration';
                    } else {
                        searchResultDiv.style.display = "none";
                        email.removeAttribute("disabled", true);
                        // searchButton.removeAttribute("disabled", true);
                        error_div.style.display = "block";
                        error_message.innerText = response.message;
                    }
                }
            });
        } else {
            window.location.href = window.location.origin + '/registration/exhibitor';
        }
    }
    
}



function formatDateTimeFromSeconds(seconds) {
    let date = new Date(); // get the current time
    date.setSeconds(date.getSeconds() + parseInt(seconds, 10));
    var day = date.getDate();
    var month = date.toLocaleString('default', { month: 'long' });
    var year = date.getFullYear();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    return day + ' ' + month + ' ' + year + ' ' + hours + ':' + minutes + ':' + seconds;
}
function generateOTP() {
    let email = document.getElementById("primary_email");
    let searchButton = document.getElementById("search_result");
    let searchResultDiv = document.getElementById("search_result_div");
    let otpResultDiv = document.getElementById("otp_result_div");
    let compName = document.getElementById("comp_name");
    let custEmail = document.getElementById("cust_email");
    let vat_no = document.getElementById('vat_no_value');
    let resend_text = document.getElementById("resend_text");
    let resend_btn = document.getElementById("resend_btn");
    document.getElementById('otp1').value = '';
    document.getElementById('otp2').value = '';
    document.getElementById('otp3').value = '';
    document.getElementById('otp4').value = '';
    let otp_error_message = document.getElementById("otp_error_message");
    otp_error_message.innerText = '';
    let timerDiv = document.getElementById("timer_div");
    timerDiv.style.display = "block";
    searchResultDiv.style.display = "block";
    error_div.style.display = "none";
    email.setAttribute("disabled", true);
    searchButton.setAttribute("disabled", true);
    otpResultDiv.style.display = "none";
    let exhibitor_email = {};
    exhibitor_email.email = email.value;
    const OTPAjax = new ajax("/otp/generate", exhibitor_email);
    // const OTPAjax = new ajax("/email/check",ex_email,"POST", "json","text");
    OTPAjax.getResponse().then(response => {
        if (response.status > 0) {
            if (response.hasOwnProperty("payload")) {
                error_div.style.display = "none";
                searchResultDiv.style.display = "none";
                otpResultDiv.style.display = "block";
                // checkbox3.classList.add("fa-check");
                resend_text.style.display = "block";
                resend_btn.style.display = "none";
                searchButton.innerHTML = "Start Again";
                // searchButton.removeAttribute("disabled", true);
                error_div.style.display = "none";
                timerDiv.style.display = 'block';
                custEmail.innerHTML = email.value;
                let payload = response.payload;
                // console.log(payload);
                var dateConvert = formatDateTimeFromSeconds(payload.expiry_duration);
                const countdownExample = document.getElementById('countdown-element');
                new Countdown(countdownExample, {
                    countdown: dateConvert,
                });
                document.addEventListener('end.mdb.countdown', (e) => {
                    var timerElement = document.getElementById("countdown-element");
                    var resend_btn = document.getElementById("resend_btn");
                    timerDiv.style.display = 'none';
                    resend_btn.style.display = 'block';
                    resend_text.style.display = "none";
                })
                compName.innerText = payload.company_name;
                if (payload.vat_no) {
                    vat_no.innerText = "VAT No: " + payload.vat_no;
                }
            } else {
                otp_error_message.innerText = response.message;
            }
        } else {
            otp_error_message.innerText = response.message;
        }
    });
}
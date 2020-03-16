import {FormVisibilityEvents} from './FormVisibilityEvents'
import {FormErrorsMapper} from './FormErrorsMapper'
import {debounce} from "./debouncer";
import {fetchRequestMaker} from './fetchRequestMaker'
import {generateFormData} from './formDataGenerator'

class SignUpForm {
    constructor() {
        // initialize fields
        this.emailInputElem = document.getElementById('email-input');
        this.emailLabelElem = document.getElementById('email-label');
        this.passwordInputElem = document.getElementById('password-input');
        this.passwordLabelElem = document.getElementById('password-label');
        this.signUpBtnElem = document.getElementById('sign-up-btn');
        this.successMsg = document.getElementById('success-msg');
        this.successMsgText = document.getElementById('success-msg-text');

        // subscribe events
        this.formErrorsMapper = new FormErrorsMapper();
        this.subscribeEvents();
    }

    subscribeEvents() {
        new FormVisibilityEvents();
        this.subscribeEmailInputValueValidationEvent();
        this.subscribeFormSubmitEvent();
    }

    subscribeEmailInputValueValidationEvent() {
        this.emailInputElem.addEventListener('keydown', debounce(() => {
            let email = this.emailInputElem.value,
                url = this.emailInputElem.dataset.url,
                data = {'email': email},
                formData = generateFormData(data);

            // If email is empty, just clear field errors & return
            if (email === '') {
                this.clearErrors(this.emailLabelElem);
                return;
            }

            fetchRequestMaker(
                url,
                "POST",
                formData
            ).then(response => {
                if(response.hasOwnProperty("errors") && response.errors.hasOwnProperty("email")) {
                    let error = `<span class="error-response-msg">${response.errors.email}</span>`;
                    this.formErrorsMapper.setFormErrors(this.emailLabelElem, error);
                } else {
                    this.clearErrors(this.emailLabelElem);
                }
            });
        }, 250, true))
    }

    subscribeFormSubmitEvent() {
        this.signUpBtnElem.addEventListener('click', () => {
            this.clearErrors(this.emailLabelElem, this.passwordLabelElem);

            let email = this.emailInputElem.value,
                password = this.passwordInputElem.value,
                url = this.signUpBtnElem.dataset.url;

            let data = {'email': email, 'password': password};
            let formData = generateFormData(data);

            fetchRequestMaker(
                url,
                "POST",
                formData
            ).then(response => {
                if(response.hasOwnProperty("errors")) {
                    Object.keys(response.errors).forEach(key => {
                        let element =
                            key === 'email' ? this.emailLabelElem : this.passwordLabelElem,
                            error = `<span class="error-response-msg">${response.errors[key]}</span>`;
                        this.formErrorsMapper.setFormErrors(element, error);
                        this.showSignUpSuccessMsg(false)
                    });
                } else if (response.hasOwnProperty('success')) {
                    let successMessage = `<span class="success-response-msg">${response.success.message}</span> `;
                    this.showSignUpSuccessMsg(true, successMessage);
                    this.clearErrors(this.emailLabelElem, this.passwordLabelElem);
                }
            });
        })
    }

    showSignUpSuccessMsg(hasToBeShown, messageToPrepend = '') {
        this.successMsgText.innerHTML = messageToPrepend + this.successMsgText.dataset.default;

        if (hasToBeShown) {
            this.successMsg.style.display = "block";
        } else {
            this.successMsg.style.display = "none";
        }
    }

    clearErrors(...elements) {
        elements.forEach(el => this.formErrorsMapper.removeFormErrors(el));
    }
}

export {SignUpForm}
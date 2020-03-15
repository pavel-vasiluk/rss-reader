import {FormVisibilityEvents} from './FormVisibilityEvents'

class LoginForm {
    constructor() {
        new FormVisibilityEvents();
        this.emailInputElem = document.getElementById('email-input');
        this.adjustEmailInputStyles();
    }

    adjustEmailInputStyles() {
        if (this.emailInputElem.value !== '') {
            this.emailInputElem.classList.add('used');
        }
    }
}

export {LoginForm}
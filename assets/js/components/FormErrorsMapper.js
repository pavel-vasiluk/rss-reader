class FormErrorsMapper {
    setFormErrors(element, error) {
        element.innerHTML = error;
        element.classList.add('alert');
    }

    removeFormErrors(element) {
        element.classList.remove('alert');
        element.innerHTML = element.dataset.field;
    }
}

export {FormErrorsMapper}
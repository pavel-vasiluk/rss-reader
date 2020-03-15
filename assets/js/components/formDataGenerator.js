function generateFormData(dataObj) {
    let formData = new FormData();
    Object.keys(dataObj).forEach(key => formData.append(key, dataObj[key]));

    return formData;
}

export {generateFormData}
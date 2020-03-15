function fetchRequestMaker(url, method, body) {
    let requestData = {method: method};
    body ? requestData['body'] = body : void 0;

    return fetch(url, requestData)
        .then((res) => {
            return res.json();
        });
}

export {fetchRequestMaker}
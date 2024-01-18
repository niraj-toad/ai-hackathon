import Cookies from 'js-cookie';
import {BeforeRequestHook} from 'ky';

const injectXsrfToken: BeforeRequestHook = request => {
    const token = Cookies.get('XSRF-TOKEN');

    if (token) {
        request.headers.set('X-XSRF-TOKEN', token);
    }

    return request;
};

export default injectXsrfToken;

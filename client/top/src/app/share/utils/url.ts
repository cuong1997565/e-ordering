import {constant} from '../../config';

export function getSiteURLFromWindowObject(obj) {
    let siteURL = '';
    siteURL = constant.BASE_WEB
    return siteURL;
}
export function getSiteURL() {
    return getSiteURLFromWindowObject(window);
}

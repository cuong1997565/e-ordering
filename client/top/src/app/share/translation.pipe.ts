import {Pipe, PipeTransform} from '@angular/core';
import {DEFAULT_LANG, DEFAULT_LANG_TOP, langDict} from '../../../../share/translation/config';
import {cookie} from './cookie';

@Pipe({
    name: 'trans',
    pure: true
})

export class TranslationPipe implements PipeTransform {

    constructor() {
    }

    public transform(value: any, args?: any): any {
        let curLang = this.getConfig('LANG_TOP', DEFAULT_LANG_TOP);

        // Deal with dash _ in category name
        let match = value.match(/^_*{/gm);
        if (match) {
            match = match[0];
            value = value.replace(match, '{');
            match = match.substring(0, match.length - 1);
        }

        if (langDict[curLang] && langDict[curLang][value]) {
            return langDict[curLang][value];
        }

        let isJson = this.isJsonString(value);
        if (isJson) {
            value = isJson[curLang] || value;
            if (match) {
                value = match + value;
            }
            return value;
        }

        return value;
    }

    getConfig(key, defaultValue?) {
        if (cookie.isSupported()) {
            if (localStorage.getItem(key) !== null) {
                return localStorage.getItem(key);
            } else {
                return defaultValue;
            }
        } else {
            return cookie.getItem(key, defaultValue);
        }
    }

    isJsonString(text) {
        if (!isNaN(text)) {
            return false;
        }
        if (text && /^[\],:{}\s]*$/.test(text.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

            return JSON.parse(text);
        } else {
            return false;
        }
    }
}

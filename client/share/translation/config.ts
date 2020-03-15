import {NAME_VN, TRANS_VN} from "./vn";
import {NAME_US, TRANS_US} from "./us";
import {NAME_JP, TRANS_JP} from "./jp";

export const DEFAULT_LANG = NAME_US;
export const DEFAULT_LANG_TOP = NAME_VN;

export const langList = [
    {
        'language': NAME_VN,
        'key': NAME_VN,
        'title': 'Tiếng Việt'
    },
    {
        'language': NAME_US,
        'key': NAME_US,
        'title': 'English'
    }
];

export const langListTop = [
    {
        'language': NAME_US,
        'key': NAME_US,
        'title': 'English',
        'src': 'assets/images/lang/lang_uk.png'
    },
    {
        'language': NAME_VN,
        'key': NAME_VN,
        'title': 'Tiếng Việt',
        'src': 'assets/images/lang/lang_vn.png'
    },
    // {
    //     'language': NAME_JP,
    //     'key': NAME_JP,
    //     'title': '日本語',
    //     'src': 'assets/image/icon/lang_jp.png'
    // }
];

export const langDict = {
    [NAME_US]: TRANS_US,
    // [NAME_JP]: TRANS_JP,
    [NAME_VN]: TRANS_VN,
};

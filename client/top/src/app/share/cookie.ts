export const cookie: any = {
    isSupported: function () {
        try {
            localStorage.setItem('localStorage', '1');
            localStorage.removeItem('localStorage');
            return true;
        } catch (e) {
            return false;
        }
    },

    createCookie: function (name, value, days) {
        let date, expires;

        if (days) {
            date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = `${name}=${value}${expires}; path=/`;
    },

    readCookie: function (name) {
        let nameEQ = name + "=",
            ca = document.cookie.split(';'),
            i, c;

        for (i = 0; i < ca.length; i++) {
            c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }

            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }
        return null;
    },

    setData: function (data) {
        data = encodeURIComponent(JSON.stringify(data));
        this.createCookie('localStorage', data, 365);
    },

    clearData: function () {
        this.createCookie('localStorage', '', 365);
    },

    getData: function () {
        let data = this.readCookie('localStorage');
        return data ? JSON.parse(decodeURIComponent(data)) : {};
    },

    clear: function () {
        this.data = {};
        this.length = 0;
        this.clearData();
    },

    getItem: function (key, def?) {
        let data = this.getData();
        return data[key] === undefined ? def : data[key];
    },
    key: function (i) {
        let ctr = 0;
        for (let k in this.data) {
            if (ctr == i) return k;
            else ctr++;
        }
        return null;
    },
    removeItem: function (key) {
        let data = this.getData();
        delete data[key];
        this.setData(data);
    },
    setItem: function (key, value) {
        let data = this.getData();
        data[key] = value + '';
        this.setData(data);
    }
};

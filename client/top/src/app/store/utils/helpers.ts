import {StateContext} from '@ngxs/store';
import {Inject} from '@angular/core';
import {ClientService} from '../client/client.service';
import {LogOut} from '../actions/users.action';

export function buildQueryString(parameters: {}): string {
    const keys = Object.keys(parameters);
    if (keys.length === 0) {
        return '';
    }

    let query = '?';
    for (let i = 0; i < keys.length; i++) {
        const key = keys[i];
        query += key + '=' + encodeURIComponent(parameters[key]);

        if (i < keys.length - 1) {
            query += '&';
        }
    }

    return query;
}

interface BindClientFuncParams {
    clientFunc: (...args: any[]) => Promise<any>;
    onRequest ?: any;
    onSuccess ?: any;
    onFailure ?: any;
    params ?: any;
    client: any;
}
const HTTP_UNAUTHORIZED = 401;
export function bindClientFunc({
    clientFunc,
    onRequest,
    onSuccess,
    onFailure,
    params = [],
    client
}: BindClientFuncParams) {
    return async (ctx: StateContext<any>) => {
        let data = null;
        try {
            data = await clientFunc(...params);
        } catch (error) {
            const {
                currentUserId
            } = client.store.snapshot().entities.users;
            if (error.status_code === HTTP_UNAUTHORIZED && error.url && error.url.indexOf('/login') === -1 && currentUserId) {
                client.setToken('');
                client.store.dispatch(new LogOut());
            }
            return {
                error
            };
        }
        if (Array.isArray(onSuccess)) {
            for (const success of onSuccess) {
                client.store.dispatch(new success(data)).toPromise();
            }
        } else if (onSuccess) {
            client.store.dispatch(new onSuccess(data));
        }
        return {
            data
        };
    };
}
function timeout(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

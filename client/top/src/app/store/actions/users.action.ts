import {Profile} from '../models/Customer';

export class GetMe {
    static readonly type = '[User] Get Me';
}

export interface ReceivedMePayload {
    id: number | null;
}
export class ReceivedMe {
    static readonly type = '[User] Received me';
    constructor(public payload: ReceivedMePayload) {}
}

export class ReceivedProfile {
    static readonly type = '[User] Received profile';
    constructor(public payload: Profile) {}
}

export interface LogInPayload {
    email: string;
    password: string;
}
export class LogIn {
    static readonly type = '[User] Log in';
    constructor(public payload: LogInPayload) {}
}

export class LogOut {
    static readonly type = '[User] Log out';
    constructor() {}
}

import {Action, Selector, State, StateContext} from '@ngxs/store';
import {ClientService} from './client/client.service';
import {GetMe, LogIn, LogInPayload, LogOut, ReceivedMe, ReceivedProfile} from './actions/users.action';
import {bindClientFunc} from './utils/helpers';
import {AppService} from '../share/app.service';
import {st} from '@angular/core/src/render3';

export interface UserStateModel {
    currentUserId: number | null;
    profiles: object;
}
@State<UserStateModel>({
    name: 'users',
    defaults: {
        currentUserId: null,
        profiles: {}
    }
})
export class UserState {
    public constructor(private client: ClientService, private appService: AppService) {}
    @Selector()
    public static getCurrentUser(state: UserStateModel) {
        return state.profiles[this.getCurrentUserId(state)];
    }
    @Selector()
    public static getCurrentUserId(state: UserStateModel) {
        return state.currentUserId;
    }
    @Selector()
    public static getCurrentDitributorUser(state: UserStateModel) {
        return state.profiles[state.currentUserId].distributor_id;
    }


    @Selector()
    public static getCurrentDitributorCodeUser(state: UserStateModel) {
        return state.profiles[state.currentUserId].distributor.code;
    }

    @Action(GetMe)
    async getMe(ctx: StateContext<UserStateModel>, action: GetMe) {
        const state = ctx.getState();
        const getMeFunc = bindClientFunc({
            clientFunc: this.client.getMe,
            onSuccess: [ReceivedMe, ReceivedProfile],
            params: [this.client.getToken()],
            client: this.client
        });
        const data = await getMeFunc(ctx);
        return data;
    }

    @Action(ReceivedMe)
    receivedMe(ctx: StateContext<UserStateModel>, action: ReceivedMe) {
        const state = ctx.getState();
        ctx.patchState({
            currentUserId: action.payload.id,
        });
    }

    @Action(LogIn)
    async logIn(ctx: StateContext<UserStateModel>, action: LogIn) {
        const state = ctx.getState();
        let data: any;
        data = await this.client.login(action.payload.email, action.payload.password);
        ctx.dispatch([new ReceivedMe({id: data.customer_id}), new ReceivedProfile(data.profile)]);
        this.appService.setConfig('E_TOKEN', data.token);
        this.client.setToken(data.token);
        return {
            data: true
        };
    }

    @Action(LogOut)
    async logOut(ctx: StateContext<UserStateModel>, action: LogOut) {
        const state = ctx.getState();
        this.appService.setConfig('E_TOKEN', '');
        this.client.setToken('');
        ctx.patchState({
            currentUserId: null,
            profiles: {}
        });
    }

    @Action(ReceivedProfile)
    receivedProfile(ctx: StateContext<UserStateModel>, action: ReceivedProfile) {
        const state = ctx.getState();
        const profiles = state.profiles;
        profiles[action.payload.id] = action.payload;
        ctx.patchState({
            profiles: profiles
        });
    }
}


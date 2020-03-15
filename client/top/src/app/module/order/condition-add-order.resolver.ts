import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, Resolve} from '@angular/router';
import {Store} from '@ngxs/store';
import {ClientService} from '../../store/client/client.service';
import {ShowLoadingNewOrderView} from '../../store/views/actions/order-view.action';
import {UserState} from '../../store/user.state';

@Injectable()
export class ConditionAddOrderResolver implements Resolve<any> {
    constructor(private store: Store, private client: ClientService) {
    }

    resolve(route: ActivatedRouteSnapshot) {
        this.store.select(UserState.getCurrentUser).subscribe((val) => {
            if (val) {
                // this.store.dispatch(new ShowLoadingNewOrderView());
            }
        })
        return Promise.all([
            this.client.getFeatureItems(),
            this.client.getFactories(),
        ]);
    }
}

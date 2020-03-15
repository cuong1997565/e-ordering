import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, Resolve} from '@angular/router';
import {Store} from '@ngxs/store';
import {ClientService} from '../../store/client/client.service';
import {ShowLoadingNewOrderView} from '../../store/views/actions/order-view.action';
import {LoadingService} from '../../share/loading.service';

@Injectable()
export class ConditionListOrderResolver implements Resolve<any> {
    constructor(private store: Store, private client: ClientService,
                private loadingService: LoadingService) {
    }

    resolve(route: ActivatedRouteSnapshot) {
        return Promise.all([
            this.client.getFeatureItems(),
            this.client.getFactories(),
            //this.client.getCategories()
        ]);
    }
}

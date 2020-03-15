import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, Resolve} from '@angular/router';
import {Store} from '@ngxs/store';
import {ClientService} from '../../store/client/client.service';

@Injectable()
export class ConditionListProductResolver implements Resolve<any> {
    constructor(private store: Store, private client: ClientService) {
    }

    resolve(route: ActivatedRouteSnapshot) {
        return Promise.all([
            this.client.getFeatureItems(),
            this.client.getFactories(),
        ]);
    }
}

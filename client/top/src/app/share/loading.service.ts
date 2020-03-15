import {Injectable} from '@angular/core';
import {Store} from '@ngxs/store';
import {ShowLoadingListOrderView, StopShowLoadingListOrderView} from '../store/views/actions/order-view.action';
import {Subject} from 'rxjs';

@Injectable()
export class LoadingService {
    public timeout = 500;
    public timeoutShowLoading: any;
    public isLoadingApplication = new Subject<boolean>();
    public keepVisionDetailOrder = new Subject<boolean>();
    public keepVisionNewOrder = new Subject<boolean>();
    public constructor(private store: Store) {}
    showLoadingApplication() {
        clearTimeout(this.timeoutShowLoading);
        this.timeoutShowLoading = setTimeout(() => {
            this.isLoadingApplication.next(true);
        }, 700);
    }
    stopShowLoadingApplication() {
        clearTimeout(this.timeoutShowLoading);
        // To avoid flashing
        this.isLoadingApplication.next(false);
        // setTimeout(() => {
        //     this.isLoadingApplication.next(false);
        // }, 50);
    }
    shouldKeepVisionForDetailOrder() {
        this.keepVisionDetailOrder.next(true);
    }
    shouldKeepVisionForNewOrder() {
        this.keepVisionNewOrder.next(true);
    }
}

import {State} from '@ngxs/store';
import {OrderViewState} from './order-view.state';

@State({
    name: 'views',
    defaults: {},
    children: [OrderViewState]
})
export class ViewsState {}

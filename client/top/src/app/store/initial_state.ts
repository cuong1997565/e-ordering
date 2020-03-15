import {State} from '@ngxs/store';
import {ProductState} from './product.state';
import {UserState} from './user.state';
import {OrderState} from './order.state';

@State({
    name: 'entities',
    defaults: {},
    children: [ProductState, UserState, OrderState]
})
export class EntitiesState {}

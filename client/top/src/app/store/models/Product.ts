import {Category} from './Category';

export interface Product {
    id: string;
    factory_id: string;
    category: Category;
    name: string;
    extra_address: string;
    image: string;
    store_id: number;
    code: string;
    price: string;
    type: number;
    email: string;
    created_at: string;
    deleted_at: string;
}

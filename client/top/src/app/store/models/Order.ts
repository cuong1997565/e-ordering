export interface Order {
    id: string;
    code?: string;
    factory_id: string;
    creator_id: number;
    user_note?: string;
    deliver_date?: string;
    deliver_actual?: string;
    creator_note?: string;
    status?: number;
    created_at?: string;
    updated_at?: string;
    canceled_date?: string;
    approved_date?: string;
    rejected_date?: string;
    completed_date?: string;
    processing_date?: string;
    confirm_date?: string;
    deliver_address: string;
    type?: number;
    products: Array<any>;
    items: Array<any>;
}

export interface CreateDraftOrderRequest {
    code?: string;
    factory_id: string;
    creator_id: number;
    user_note?: string;
    deliver_date?: string;
    deliver_actual?: string;
    creator_note?: string;
    products: Array<any>;
    items: Array<any>;
}

export interface UpdateDraftOrderRequest {
    code?: string;
    factory_id: string;
    creator_id: number;
    user_note?: string;
    deliver_date?: string;
    deliver_actual?: string;
    creator_note?: string;
    products: Array<any>;
    items: Array<any>;
}

export interface CreateOrderRequest {
    code?: string;
    factory_id: string;
    creator_id: number;
    user_note?: string;
    deliver_date?: string;
    deliver_actual?: string;
    creator_note?: string;
    products: Array<any>;
    items: Array<any>;
}


export interface UpdateOrderRequest {
    id: string;
    code?: string;
    factory_id: string;
    creator_id: number;
    user_note?: string;
    deliver_date?: string;
    deliver_actual?: string;
    creator_note?: string;
    products: Array<any>;
    items: Array<any>;
}

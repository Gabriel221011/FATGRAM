import { EventEmitter } from '../../../../../stencil-public-runtime';
import { LineItemData } from "../../../../../types";
/**
 * This component listens for stock requirements and displays a dialog to the user.
 */
export declare class ScCheckoutStockAlert {
    /** Stock errors */
    stockErrors: Array<any>;
    /** Toggle line item event */
    scUpdateLineItem: EventEmitter<LineItemData>;
    /** Is it busy */
    busy: boolean;
    /** Update stock error. */
    error: string;
    /** Get the out of stock line items. */
    getOutOfStockLineItems(): import("src/types").LineItem[];
    /**
     * Build line items with adjusted quantities for out-of-stock items.
     *
     * Returns all line items, with out-of-stock items adjusted to max available stock.
     */
    getStockAdjustedLineItems(): {
        variant?: string;
        id: string;
        price_id: string;
        quantity: number;
    }[];
    /**
     * Update the checkout line items stock to the max available.
     */
    onSubmit(): Promise<void>;
    render(): any;
}

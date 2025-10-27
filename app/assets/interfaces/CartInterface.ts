/**
 * Cart Interface
 * 
 * Defines the structure for cart data in the Orders sprinkle.
 */

export interface CartLine {
  id?: number;
  order_id?: number;
  product_catalog_id?: number;
  ref_id1?: number;
  ref_id2?: number;
  line_no: number;
  type?: string;
  description: string;
  unit_price: number;
  quantity: number;
  net_amount: number;
  tax: number;
  discount: number;
  gross_amount: number;
  balance_amount: number;
  notes?: string;
  status: 'A' | 'R' | 'D'; // Active, Removed, Deleted
}

export interface CartOrder {
  id?: number;
  year?: number;
  name: string;
  description?: string;
  order_number?: string;
  contract_number?: string;
  order_status?: string;
  type?: string;
  parent_id?: number;
  user_id: number;
  approver_id?: number;
  order_date?: string;
  expiry_date?: string;
  net_amount: number;
  tax: number;
  discount: number;
  epay_commission: number;
  gross_amount: number;
  payment_type?: string;
  payment_ref?: string;
  payment_link?: string;
  payment_date?: string;
  payment_note?: string;
  notes?: string;
  meta?: Record<string, any>;
  status: string;
}

export interface Cart {
  order: CartOrder;
  lines: CartLine[];
}

export interface PaymentOption {
  id: string;
  label: string;
}

export interface CartTotals {
  quantity: number;
  net_amount: number;
  gross_amount: number;
  tax: number;
  discount: number;
}

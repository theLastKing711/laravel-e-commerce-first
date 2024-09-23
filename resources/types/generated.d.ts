declare namespace App.Data.Admin.Admin {
export type AdminData = {
id: number;
name: string;
created_at: string;
};
export type CreateAdminData = {
name: string;
password: string;
};
}
declare namespace App.Data.Admin.Category {
export type CategoryData = {
id: number;
parent_id: number | null;
name: string;
image: string | null;
created_at: string;
parent: App.Data.Admin.Category.CategoryData | null;
};
export type CreateCategoryData = {
name: string;
parent_id: number | null;
image: any | null;
};
export type ParentCategoryIdsData = {
ids: { [key: number]: number };
};
export type UpdateCategoryData = {
name: string;
image: any | null;
};
}
declare namespace App.Data.Admin.Product {
export type CreateProductData = {
name: string;
price: string;
description: string;
is_most_buy: boolean;
is_active: boolean;
unit: App.Enum.Unit;
image: any | null;
};
export type PaginatedProductData = {
data: { [key: number]: any } | Array<any>;
current_page: number;
per_page: number;
total: number;
};
export type ProductListData = {
id: number;
name: string;
};
export type UpdateProductData = {
name: string;
price: string;
description: string;
is_most_buy: boolean;
is_active: boolean;
unit: App.Enum.Unit;
image: any | null;
};
}
declare namespace App.Data.Admin.Product.QueryParameters {
export type ProductNameQueryParameterData = {
name: string | null;
};
}
declare namespace App.Enum {
export type AccountRegistrationStep = 0 | 1 | 2 | 3;
export type Day = 0 | 1 | 2 | 3 | 4 | 5 | 6;
export type Gender = 0 | 1;
export type NotificationType = 0 | 1 | 2 | 3;
export type OrderStatus = 1 | 2 | 3 | 4 | 5 | 6;
export type Unit = 1 | 2 | 3 | 4 | 5;
}
declare namespace App.Enum.Auth {
export type RolesEnum = 'admin' | 'user' | 'driver' | 'store';
}

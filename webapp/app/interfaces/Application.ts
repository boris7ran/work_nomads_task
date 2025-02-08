export interface ApplicationInterface {
    id: string;
    name: string;
    roles: Array<RoleInterface>;
}

export interface RoleInterface {
    id: string,
    name: string,
}
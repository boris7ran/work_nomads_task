import api from '@/app/http/Client';

import { UserInterface } from '@/app/interfaces/User';
import { UserFormInterface } from '@/app/interfaces/UserForm';

export const getUsers = async (search: string): Promise<Array<UserInterface>> => {
    const response = await api.get(`/users`, search ? { params: { search }} : undefined);

    return response.data;
}

export const getUser = async (userId: string): Promise<UserInterface> => {
    const response = await api.get(`/users/${userId}`);

    return response.data;
};

export const createUser = async (userData: UserFormInterface) => {
    const response = await api.post('/users', userData);

    return response.data;
};

export const updateUser = async (userId: string, userData: Partial<UserFormInterface>) => {
    const response = await api.put(`/users/${userId}`, userData);

    return response.data;
};

export const deleteUser = async (userId: string) => {
    await api.delete(`/users/${userId}`);
}

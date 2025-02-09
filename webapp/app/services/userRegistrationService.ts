import api from '@/app/http/Client';

import { UserRegistrationFormInterface, UserRegistrationInterface } from '@/app/interfaces/UserRegistration';

export const getUserRegistration = async (userId: string, applicationId: string): Promise<UserRegistrationInterface> => {
    const response = await api.get(`/users/registrations/${userId}/${applicationId}`);

    return response.data;
}

export const createUserRegistration = async (userId: string, applicationId: string, userRegistrationData: UserRegistrationFormInterface): Promise<UserRegistrationInterface> => {
    const response = await api.post(`/users/registrations/${userId}`, {
        applicationId,
        ...userRegistrationData,
    });

    return response.data;
}

export const editUserRegistration = async (userId: string, applicationId: string, userRegistrationData: UserRegistrationFormInterface): Promise<UserRegistrationInterface> => {
    const response = await api.put(`/users/registrations/${userId}/${applicationId}`, {
        applicationId,
        ...userRegistrationData,
    });

    return response.data;
}

export const deleteUserRegistration = async (userId: string, applicationId: string): Promise<> => {
    const response = await api.delete(`/users/registrations/${userId}/${applicationId}`);

    return response.data;
}

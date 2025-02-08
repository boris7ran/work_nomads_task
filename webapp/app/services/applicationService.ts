import api from '@/app/http/Client';

import { ApplicationInterface } from '@/app/interfaces/Application';

export const getApplications = async (): Promise<Array<ApplicationInterface>> => {
    const response = await api.get(`/applications`);

    return response.data;
}

export const getApplication = async (applicationId: string): Promise<ApplicationInterface> => {
    const response = await api.get(`/applications/${applicationId}`);

    return response.data;
}

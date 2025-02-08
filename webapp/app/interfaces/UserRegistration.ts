export interface UserRegistrationInterface
{
    id: string;
    applicationId: string;
    username: string;
    data: [] | null;
    roles: Array<string>;
    token: Array<string>;
    usernameStatus: string;
    verified: boolean;
    timezone: string;
    preferredLanguages: Array<string>;
    insertInstant: Date | null;
    lastLoginInstant: Date | null;
    lastUpdateInstant: Date | null;
    verifiedInstant: Date | null;
}

export interface UserRegistrationFormInterface
{
    username: string | null;
    roles: Array<string>;
    preferredLanguages: Array<string>;
    timezone: string;
}
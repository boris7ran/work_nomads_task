import React, { useMemo, useState } from 'react';

import { UserRegistrationFormInterface, UserRegistrationInterface } from '@/app/interfaces/UserRegistration';
import styles from '@/app/components/user/userForm.module.css';
import { ApplicationInterface } from '@/app/interfaces/Application';

interface UserFormProps {
    initialData: UserRegistrationInterface | null;
    onSubmit: (userRegistration: UserRegistrationFormInterface) => void;
    application: ApplicationInterface,
}

const EMPTY_FORM_DATA: UserRegistrationFormInterface = {
    username: '',
    roles: [],
    preferredLanguages: [],
    timezone: '',
};

export const UserRegistrationForm: React.FC<UserFormProps> = ({ initialData, onSubmit, application }) => {
    const [formData, setFormData] = useState<UserRegistrationFormInterface>(
        initialData ? {
            username: initialData.username || EMPTY_FORM_DATA.username,
            roles: initialData.roles || EMPTY_FORM_DATA.roles,
            preferredLanguages: initialData.preferredLanguages || EMPTY_FORM_DATA.preferredLanguages,
            timezone: initialData.timezone || EMPTY_FORM_DATA.timezone,
        } : EMPTY_FORM_DATA
    );

    const LANGUAGES = useMemo(() => [
        { label: "English", value: "en" },
        { label: "Spanish", value: "es" },
        { label: "French", value: "fr" },
        { label: "German", value: "de" },
        { label: "Italian", value: "it" }
    ], []);

    const TIMEZONES = useMemo(() => [
        { label: "UTC", value: "UTC" },
        { label: "Eastern Time (US & Canada)", value: "America/New_York" },
        { label: "Central European Time", value: "Europe/Berlin" },
        { label: "India Standard Time", value: "Asia/Kolkata" },
        { label: "Japan Standard Time", value: "Asia/Tokyo" }
    ], []);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    const handleMultiSelectChange = (e: React.ChangeEvent<HTMLSelectElement>, key: "roles" | "preferredLanguages") => {
        const selectedValues = Array.from(e.target.selectedOptions, option => option.value);
        setFormData({ ...formData, [key]: selectedValues });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        onSubmit(formData);
    };

    return (
        <div className={styles.formContainer}>
            <form onSubmit={handleSubmit} className={styles.form}>
                <label htmlFor="application">Application:</label>
                <input id="application" type="text" value={application.name} disabled/>

                <label htmlFor="username">Username:</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value={formData.username ?? ''}
                    onChange={handleChange}
                    required
                />

                <label htmlFor="roles">Roles:</label>
                <select
                    id="roles"
                    name="roles"
                    multiple
                    onChange={(e) => handleMultiSelectChange(e, "roles")}
                    className={styles.multiSelect}
                    value={formData.roles}
                >
                    {application.roles.map(role => (
                        <option key={role.id} value={role.name}>
                            {role.name}
                        </option>
                    ))}
                </select>

                <label htmlFor="preferredLanguages">Preferred Languages:</label>
                <select
                    id="preferredLanguages"
                    name="preferredLanguages"
                    multiple
                    onChange={(e) => handleMultiSelectChange(e, "preferredLanguages")}
                    className={styles.multiSelect}
                    value={formData.preferredLanguages}
                >
                    {LANGUAGES.map(lang => (
                        <option key={lang.value} value={lang.value}>
                            {lang.label}
                        </option>
                    ))}
                </select>

                <label htmlFor="timezone">Timezone:</label>
                <select
                    id="timezone"
                    name="timezone"
                    value={formData.timezone}
                    onChange={handleChange}
                    className={styles.select}
                >
                    <option value="">-- Select Timezone --</option>
                    {TIMEZONES.map(tz => (
                        <option key={tz.value} value={tz.value}>
                            {tz.label}
                        </option>
                    ))}
                </select>

                <button type="submit" className={styles.submitButton}>Submit</button>
            </form>
        </div>
    );
};

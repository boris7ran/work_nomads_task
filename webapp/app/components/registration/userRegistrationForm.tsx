import React, { useState } from 'react';

import { UserRegistrationFormInterface, UserRegistrationInterface } from '@/app/interfaces/UserRegistration';
import styles from '@/app/components/user/userForm.module.css';
import { ApplicationInterface } from '@/app/interfaces/Application';

interface UserFormProps {
    initialData: UserRegistrationInterface | null;
    onSubmit: (userRegistration: UserRegistrationFormInterface) => void;
    application: ApplicationInterface,
}

const LANGUAGES = [
    { label: "English", value: "en" },
    { label: "Spanish", value: "es" },
    { label: "French", value: "fr" },
    { label: "German", value: "de" },
    { label: "Italian", value: "it" }
];

const TIMEZONES = [
    { label: "UTC", value: "UTC" },
    { label: "Eastern Time (US & Canada)", value: "America/New_York" },
    { label: "Central European Time", value: "Europe/Berlin" },
    { label: "India Standard Time", value: "Asia/Kolkata" },
    { label: "Japan Standard Time", value: "Asia/Tokyo" }
];

const EMPTY_FORM_DATA: UserRegistrationFormInterface = {
    username: '',
    roles: [],
    preferredLanguages: [],
    timezone: '',
};

export const UserRegistrationForm: React.FC<UserFormProps> = ({ initialData, onSubmit, application }) => {
    const [formData, setFormData] = useState<UserRegistrationFormInterface>(
        initialData ? {
            username: initialData.username || '',
            roles: initialData.roles || [],
            preferredLanguages: initialData.preferredLanguages || [],
            timezone: initialData.timezone || '',
        } : EMPTY_FORM_DATA
    );

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    const handleMultiSelectChange = (e: React.ChangeEvent<HTMLSelectElement>, key: "roles" | "languages") => {
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
                <label>Application:</label>
                <input type="text" value={application.name} disabled/>

                <label>Username:</label>
                <input type="text" name="username" value={formData.username ?? ''} onChange={handleChange} required/>

                <label>Roles:</label>
                <select name="roles" multiple onChange={(e) => handleMultiSelectChange(e, "roles")}
                        className={styles.multiSelect}>
                    {application.roles.map(role => (
                        <option key={role} value={role} selected={formData.roles.includes(role)}>
                            {role}
                        </option>
                    ))}
                </select>

                <label>Preferred Languages:</label>
                <select name="languages" multiple onChange={(e) => handleMultiSelectChange(e, "languages")}
                        className={styles.multiSelect}>
                    {LANGUAGES.map(lang => (
                        <option key={lang.value} value={lang.value}
                                selected={formData.preferredLanguages.includes(lang.value)}>
                            {lang.label}
                        </option>
                    ))}
                </select>

                <label>Timezone:</label>
                <select name="timezone" value={formData.timezone} onChange={handleChange} className={styles.select}>
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

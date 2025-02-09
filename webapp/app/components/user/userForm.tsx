import React, { useState } from 'react';

import { UserFormInterface } from '@/app/interfaces/UserForm';
import { UserInterface } from "@/app/interfaces/User";

import styles from './userForm.module.css';

interface UserFormProps {
    initialData: UserInterface | null;
    onSubmit: (userData: UserFormInterface) => Promise<void>;
}

const UserForm: React.FC<UserFormProps> = ({ initialData, onSubmit }) => {
    const [formData, setFormData] = useState<UserFormInterface>({
        email: initialData?.email || '',
        firstName: initialData?.firstName || '',
        lastName: initialData?.lastName || '',
        password: '',
        birthDate: initialData?.birthDate || '',
    });

    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setError(null);

        try {
            await onSubmit(formData);
        } catch (err) {
            setError('Submission failed. Please try again.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className={styles.formContainer}>
            <form onSubmit={handleSubmit} className={styles.form}>
                {error && <p className={styles.error}>{error}</p>}

                <label htmlFor="email">Email:</label>
                <input type="email" id="email" name="email" value={formData.email} onChange={handleChange} required />

                <label htmlFor="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" value={formData.firstName} onChange={handleChange} />

                <label htmlFor="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" value={formData.lastName} onChange={handleChange} />

                <label htmlFor="password">Password:</label>
                <input type="password" id="password" name="password" value={formData.password} minLength={9} onChange={handleChange} required />

                <label htmlFor="birthDate">Birth Date:</label>
                <input type="date" id="birthDate" name="birthDate" value={formData.birthDate} onChange={handleChange} />

                <button type="submit" className={styles.submitButton} disabled={loading}>
                    {loading ? 'Submitting...' : 'Submit'}
                </button>
            </form>
        </div>
    );
};

export default UserForm;

import React, { useState } from 'react';

import { UserFormInterface } from '@/app/interfaces/UserForm';
import { UserInterface } from "@/app/interfaces/User";

import styles from './userForm.module.css';

interface UserFormProps {
    initialData: UserInterface | null;
    onSubmit: (userData: UserFormInterface) => Promise<void>;
}

const UserForm: React.FC<UserFormProps> = ({ initialData, onSubmit }) => {
    const [formData, setFormData] = useState<UserFormInterface>(
        initialData ? {
            ...initialData,
            password: '',
        } : {
            email: '',
            firstName: '',
            lastName: '',
            password: '',
            birthDate: '',
        }
    );

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        await onSubmit(formData);
    };

    return (
        <div className={styles.formContainer}>
            <form onSubmit={handleSubmit} className={styles.form}>
                <label>Email:</label>
                <input type="email" name="email" value={formData.email} onChange={handleChange} required/>

                <label>First Name:</label>
                <input type="text" name="firstName" value={formData.firstName} onChange={handleChange}/>

                <label>Last Name:</label>
                <input type="text" name="lastName" value={formData.lastName} onChange={handleChange}/>

                <label>Password:</label>
                <input type="password" name="password" value={formData.password} onChange={handleChange}/>

                <label>Birth Date:</label>
                <input type="date" name="birthDate" value={formData.birthDate} onChange={handleChange}/>

                <button type="submit" className={styles.submitButton}>Submit</button>
            </form>
        </div>
    );
};

export default UserForm;

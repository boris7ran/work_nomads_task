import React, { useState } from 'react';
import styles from './passwordForgottenForm.module.css';

interface UserFormProps {
    onSubmit: (passwordFormData: PasswordForgottenFormInterface) => Promise<void>;
}

export interface PasswordForgottenFormInterface {
    email: string;
}

const UserForm: React.FC<UserFormProps> = ({ onSubmit }) => {
    const [formData, setFormData] = useState<PasswordForgottenFormInterface>({ email: '' });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        await onSubmit(formData);
    };

    return (
        <div className={styles.userFormContainer}>
            <form className={styles.userForm} onSubmit={handleSubmit}>
                <label>Email:</label>
                <input
                    type="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    required
                />
                <button type="submit">Submit</button>
            </form>
        </div>
    );
};

export default UserForm;

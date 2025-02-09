import React, { useState } from 'react';

interface UserFormProps {
    onSubmit: (passwordFormData: PasswordFormInterface) => Promise<void>;
}

export interface PasswordFormInterface {
    password: string,
    confirmPassword: string,
}

const UserForm: React.FC<UserFormProps> = ({ onSubmit }) => {
    const [formData, setFormData] = useState<PasswordFormInterface>({
        password: '',
        confirmPassword: '',
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        await onSubmit(formData);
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <label>Password:</label>
                <input type="password" name="password" value={formData.password} onChange={handleChange}/>

                <label>Confirm Password:</label>
                <input type="password" name="confirmPassword" value={formData.confirmPassword} onChange={handleChange}/>

                <button type="submit">Submit</button>
            </form>
        </div>
    );
};

export default UserForm;

import React, { useState } from 'react';

interface UserFormProps {
    onSubmit: (passwordFormData: PasswordForgottenFormInterface) => Promise<void>;
}

export interface PasswordForgottenFormInterface {
    email: string,
}

const UserForm: React.FC<UserFormProps> = ({ onSubmit }) => {
    const [formData, setFormData] = useState<PasswordForgottenFormInterface>({
        email: '',
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
                <label>Email:</label>
                <input type="email" name="email" value={formData.email} onChange={handleChange}/>

                <button type="submit">Submit</button>
            </form>
        </div>
    );
};

export default UserForm;

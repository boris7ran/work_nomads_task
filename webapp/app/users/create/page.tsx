'use client'

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';

import UserForm from '@/app/components/user/userForm';
import { createUser } from '@/app/services/userService';
import { UserFormInterface } from '@/app/interfaces/UserForm';
import Message, { MessageInterface, MessageType } from '@/app/components/message/message';

export default function UserCreatePage() {
    const router = useRouter();
    const [loading, setLoading] = useState<boolean>(false);
    const [message, setMessage] = useState<MessageInterface | null>(null);

    const handleCreateUser = async (userData: UserFormInterface) => {
        setLoading(true);

        try {
            await createUser(userData);
            setMessage({ text: 'User created successfully', type: MessageType.success });
            router.push('/users');
        } catch (error) {
            const errorMessage = error instanceof Error ? error.message : 'Error occurred during user creation';
            setMessage({ text: errorMessage, type: MessageType.error });
        } finally {
            setLoading(false);
        }
    }

    return (
        <div>
            {message && (
                <Message type={message.type} text={message.text} onClose={() => setMessage(null)}/>
            )}
            <UserForm onSubmit={handleCreateUser} initialData={null} />
            {loading && <p>Creating user...</p>}
        </div>
    )
}
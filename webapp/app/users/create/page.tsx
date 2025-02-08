'use client'

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';

import UserForm from '@/app/components/user/userForm';
import { createUser } from '@/app/services/userService';
import { UserFormInterface } from '@/app/interfaces/UserForm';
import Message, { MessageInterface, MessageType } from '@/app/components/message/message';

export default function UserCreatePage() {
    const router = useRouter();

    const [message, setMessage] = useState<MessageInterface | null>(null);

    const handleCreateUser = async (userData: UserFormInterface) => {
        try {
            await createUser(userData);

            router.push('/users');
        } catch (error) {
            setMessage({ text: 'Error occurred during user creaton', type: MessageType.error });}
    };

    return (
        <div>
            {message && (
                <Message type={message.type} text={message.text} onClose={() => setMessage(null)}/>
            )}
            <UserForm onSubmit={handleCreateUser}  initialData={null}/>
        </div>
    )
}
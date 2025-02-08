'use client'

import React, { useEffect, useState } from 'react';

import {UserInterface} from '@/app/interfaces/User';
import {UserFormInterface} from '@/app/interfaces/UserForm';
import UserForm from '@/app/components/user/userForm';
import { getUser, updateUser } from '@/app/services/userService';
import Message, { MessageInterface, MessageType } from "@/app/components/message/message";
import { Registration } from '@/app/components/registration/registration';
import { ApplicationInterface } from "@/app/interfaces/Application";
import { getApplications } from '@/app/services/applicationService';

export default function UserCreatePage({ params }: { params: Promise<{ userId: string }> }) {
    const [userData, setUserData] = useState<UserInterface | null>(null);
    const [applications, setApplications] = useState<ApplicationInterface[] | null>(null);
    const [message, setMessage] = useState<MessageInterface | null>(null);

    async function fetchUsers() {
        try {
            const usersData = await getUser((await params).userId);
            setUserData(usersData);
        } catch (error) {
            console.error('Error fetching users:', error);
        }
    }

    async function fetchApplications() {
        try {
            const applicationsData = await getApplications();
            setApplications(applicationsData);
        } catch (error) {
            console.error('Error fetching users:', error);
        }
    }

    useEffect(() => {
        fetchUsers();
        fetchApplications();
    }, []);

    const handleUpdateUser = async (userData: UserFormInterface) => {
        try {
            await updateUser((await params).userId, userData);
            setMessage({ text: 'User updated successfully', type: MessageType.success });
        } catch (error) {
            setMessage({ text: 'Error occurred during user update', type: MessageType.error });
        }
    };

    return (
        <div>
            {message && (
                <Message type={message.type} text={message.text} onClose={() => setMessage(null)}/>
            )}
            <UserForm
                key={userData?.id}
                initialData={userData}
                onSubmit={handleUpdateUser}
            />
            {userData && userData.id && applications && (
                <Registration userId={userData.id} applications={applications}/>
            )}
        </div>
    )
}
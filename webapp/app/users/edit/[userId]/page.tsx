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

export default function UserEditPage({ params }: { params: Promise<{ userId: string }> }) {
    const [userData, setUserData] = useState<UserInterface | null>(null);
    const [applications, setApplications] = useState<ApplicationInterface[] | null>(null);
    const [message, setMessage] = useState<MessageInterface | null>(null);
    const [userId, setUserId] = useState<string | null>(null);

    const fetchData = async () => {
        const { userId } = await params;
        setUserId(userId);
        await fetchUsers(userId);
        await fetchApplications();
    };

    useEffect(() => {
        fetchData();
    }, []);

    const fetchUsers = async (userId: string) => {
        try {
            const usersData = await getUser(userId);
            setUserData(usersData);
        } catch (error) {
            setMessage({ text: `Error fetching users:', ${error}`, type: MessageType.error });
        }
    };

    const fetchApplications = async () => {
        try {
            const applicationsData = await getApplications();
            setApplications(applicationsData);
        } catch (error) {
            console.error('Error fetching applications:', error);
            setMessage({ text: `Error fetching applications:', ${error}`, type: MessageType.error });
        }
    };

    const handleUpdateUser = async (userData: UserFormInterface) => {
        if (!userId) return;

        try {
            await updateUser(userId, userData);
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
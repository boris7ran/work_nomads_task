'use client'

import React, {useEffect, useState} from 'react';
import { useRouter } from 'next/navigation';

import { UserRegistrationForm } from '@/app/components/registration/userRegistrationForm';
import { UserRegistrationFormInterface } from '@/app/interfaces/UserRegistration';
import Message, { MessageInterface, MessageType } from '@/app/components/message/message';
import { createUserRegistration } from '@/app/services/userRegistrationService';
import { getApplication } from "@/app/services/applicationService";
import { ApplicationInterface } from "@/app/interfaces/Application";

export default function UserRegistrationCreatePage ({ params }: { params: Promise<{ userId: string, applicationId: string }> }) {
    const router = useRouter();

    const [message, setMessage] = useState<MessageInterface | null>(null);
    const [application, setApplication] = useState<ApplicationInterface | null>(null);

    const fetchApplication = async () => {
        try {
            const applicationData = await getApplication((await params).applicationId);
            setApplication(applicationData);
        } catch (error) {
            console.error('Error fetching users:', error);
        }
    }

    useEffect(() => {
        fetchApplication();
    }, []);

    const handleSubmit = async (userRegistration: UserRegistrationFormInterface) => {
        const { userId, applicationId } = await params;

        try {
            await createUserRegistration(userId, applicationId, userRegistration);
            router.push(`/users/edit/${userId}`);
        } catch (error) {
            setMessage({ text: 'Error occurred during User registration create', type: MessageType.error });
        }
    }

    return (
        <>
            {message && (
                <Message type={message.type} text={message.text} onClose={() => setMessage(null)}/>
            )}
            {application &&
                <UserRegistrationForm initialData={null} onSubmit={handleSubmit} application={application}/>
            }
        </>
    );
}
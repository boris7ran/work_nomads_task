'use client'

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';

import Message, { MessageInterface, MessageType } from "@/app/components/message/message";
import PasswordChangeForm, { PasswordFormInterface } from "@/app/components/passwordChangeForm/passwordChangeForm";
import { changePassword } from "@/app/services/userService";

export default function ChangePasswordPage({ params }: { params: Promise<{ changePasswordId: string }> }) {
    const router = useRouter();
    const [message, setMessage] = useState<MessageInterface | null>(null);

    const handlePasswordChange = async (passwordChangeForm: PasswordFormInterface) => {
        if (passwordChangeForm.password !== passwordChangeForm.confirmPassword) {
            setMessage({text: 'Passwords mismatch', type: MessageType.error});

            return;
        }

        try {
            await changePassword((await params).changePasswordId, passwordChangeForm.password);

            router.push('/login');
        } catch (error) {
            setMessage({text: 'Password Changed Failed', type: MessageType.error});
        }
    }

    return (
        <div>
            {message && (
                <Message type={message.type} text={message.text} onClose={() => setMessage(null)}/>
            )}
            <PasswordChangeForm onSubmit={handlePasswordChange} />
        </div>
    )
}
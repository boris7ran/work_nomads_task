'use client'

import React, { useState } from 'react';

import Message, { MessageInterface, MessageType } from '@/app/components/message/message';
import PasswordForgottenForm, {
    PasswordForgottenFormInterface
} from '@/app/components/PasswordForgottenForm/passwordForgottenForm';
import { userPasswordForgotten } from '@/app/services/userService';

export default function PasswordForgottenPage() {
    const [message, setMessage] = useState<MessageInterface | null>(null);

    const handlePasswordForgotten = async (passwordForgottenForm: PasswordForgottenFormInterface) => {
        try {
            await userPasswordForgotten(passwordForgottenForm.email);

            setMessage({text: 'Password Forgotten email sent', type: MessageType.success});
        } catch (error) {
            setMessage({text: 'Password Forgotten Failed', type: MessageType.error});
        }
    }

    return (
        <div>
            {message && (
                <Message type={message.type} text={message.text} onClose={() => setMessage(null)}/>
            )}
            <PasswordForgottenForm onSubmit={handlePasswordForgotten} />
        </div>
    )
}
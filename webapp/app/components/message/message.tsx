import React from 'react';

import styles from './message.module.css';

export enum MessageType {
    success = 'success',
    info = 'info',
    error = 'error',
}

interface MessageProps extends MessageInterface{
    onClose: () => void;
}

export interface MessageInterface {
    type: MessageType;
    text: string;
}

const Message: React.FC<MessageProps> = ({ type, text, onClose }) => {
    return (
        <div className={`${styles.message} ${styles[type]}`}>
            <span>{text}</span>
            <button className={styles.closeButton} onClick={() => onClose()}>×</button>
        </div>
    );
};

export default Message;

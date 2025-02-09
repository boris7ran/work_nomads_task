'use client'

import { useEffect, useState } from 'react';
import Link from 'next/link';

import { UserInterface } from '@/app/interfaces/User';
import { getUsers, deleteUser } from '@/app/services/userService';
import Message, { MessageInterface, MessageType } from '@/app/components/message/message';
import styles from './page.module.css';
import UserTable from '../components/user/userTable';

export default function UsersPage() {
    const [users, setUsers] = useState<UserInterface[]>([]);
    const [message, setMessage] = useState<MessageInterface | null>(null);
    const [search, setSearch] = useState<string>('');
    const [loading, setLoading] = useState<boolean>(false);

    async function fetchUsers(search: string) {
        setLoading(true);

        try {
            const usersData = await getUsers(search);
            setUsers(usersData);
        } catch (error) {
            setMessage({ text: 'Error occurred during fetching of users', type: MessageType.error });
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        fetchUsers(search);
    }, [search]);

    const handleDeleteUser = async (userId: string) => {
        const previousUsers = [...users];

        try {
            await deleteUser(userId);

            setMessage({ text: 'User deleted successfully', type: MessageType.success });
            fetchUsers(search);
        } catch (error: unknown) {
            setUsers(previousUsers);
            setMessage({ text: 'Error occurred during deletion of user', type: MessageType.error });
        }
    }

    return (
        <div className={styles.container}>
            {message && <Message type={message.type} text={message.text} onClose={() => setMessage(null)} />}
            {loading && <p>Loading...</p>}
            <div>
                <h1>Users List</h1>
                <label htmlFor="search">Search:</label>
                <input id="search" onChange={(e) => { setSearch(e.target.value) }} value={search} />
            </div>
            <UserTable users={users} onDeleteUser={handleDeleteUser} />

            <div className={styles.createUserContainer}>
                <Link href="/users/create" className={styles.createUserButton}>
                    Create User
                </Link>
            </div>
        </div>
    );
}
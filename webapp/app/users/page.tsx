'use client'

import { useEffect, useState } from 'react';
import Link from 'next/link';

import { UserInterface } from '@/app/interfaces/User';
import { getUsers, deleteUser } from '@/app/services/userService';
import Message, { MessageInterface, MessageType } from '@/app/components/message/message';
import styles from './page.module.css';

export default function UsersPage() {
    const [users, setUsers] = useState<UserInterface[]>([]);
    const [message, setMessage] = useState<MessageInterface | null>(null);
    const [search, setSearch] = useState<string>('');

    async function fetchUsers(search: string) {
        try {
            const usersData = await getUsers(search);
            setUsers(usersData);
        } catch (error) {
            setMessage({ text: 'Error occurred during fetching of users', type: MessageType.error });
        }
    }

    useEffect(() => {
        fetchUsers(search);
    }, [search]);

    const handleDeleteUser = async (userId: string) => {
        try {
            await deleteUser(userId);

            setMessage({ text: 'User deleted successfully', type: MessageType.success });
            fetchUsers(search);
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
        } catch (error: any) {
            setMessage({ text: 'Error occurred during deletion of user', type: MessageType.error });
        }
    }

    return (
        <div className={styles.container}>
            {message && (
                <Message type={message.type} text={message.text} onClose={() => setMessage(null)}/>
            )}
            <div>
                <h1>Users List</h1>
                <label>
                    <>Search:</>
                    <input onChange={(e) => { setSearch(e.target.value)}} value={search}/>
                </label>
            </div>
            <table className={styles.table}>
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Birth Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                {users.map((user: UserInterface) => (
                    <tr key={user.id}>
                        <td>{user.firstName}</td>
                        <td>{user.lastName}</td>
                        <td>{user.email}</td>
                        <td>{user.birthDate}</td>
                        <td>
                            <Link href={`/users/edit/${user.id}`} className={`${styles.linkButton} ${styles.editButton}`}>Edit</Link>
                            {user.id && (
                                <button className={`${styles.linkButton} ${styles.deleteButton}`} onClick={() => { handleDeleteUser(user.id) }}>Delete</button>
                            )}
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>
            <div className={styles.createUserContainer}>
                <Link href="/users/create" className={styles.createUserButton}>
                    Create User
                </Link>
            </div>
        </div>
    );
}

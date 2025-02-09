import React from 'react';
import Link from 'next/link';

import { UserInterface } from '@/app/interfaces/User';
import styles from './userTable.module.css';

interface UserTableProps {
    users: UserInterface[];
    onDeleteUser: (userId: string) => void;
}

const UserTable: React.FC<UserTableProps> = ({ users, onDeleteUser }) => {
    return (
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
            {users.map((user) => (
                <tr key={user.id}>
                    <td>{user.firstName}</td>
                    <td>{user.lastName}</td>
                    <td>{user.email}</td>
                    <td>{user.birthDate}</td>
                    <td>
                        <Link href={`/users/edit/${user.id}`} className={`${styles.linkButton} ${styles.editButton}`}>Edit</Link>
                        {user.id && (
                            <button
                                className={`${styles.linkButton} ${styles.deleteButton}`}
                                onClick={() => user.id && onDeleteUser(user.id)}
                            >
                                Delete
                            </button>
                        )}

                    </td>
                </tr>
            ))}
            </tbody>
        </table>
    );
};

export default UserTable;
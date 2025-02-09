'use client'

import React, { useState } from 'react';

import api from '@/app/http/Client';
import styles from './page.module.css';

import { useRouter } from 'next/navigation';
import Link from "next/link";

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const router = useRouter();

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        setError('');

        try {
            await api.post('/login', { email, password });

            router.push('/');
        } catch (error) {
            setError(error instanceof Error ? error.message : 'Login failed');
        }
    }

    return (
        <div className={styles.loginContainer}>
            <form className={styles.loginForm} onSubmit={handleSubmit}>
                <label>
                    <span>Email</span>
                    <input
                        name="email"
                        type="text"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                    />
                </label>
                <label>
                    <span>Password</span>
                    <input
                        name="password"
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                    />
                </label>
                {error && <p className={styles.error}>{error}</p>}
                <button type="submit">Login</button>

                <a href="http://localhost:8080/oauth/redirect" >
                    <button className={styles.googleSsoButton} type="button">
                        Sign in with Google
                    </button>
                </a>
                <Link href={'/password-forgotten'}>Password Forgotten</Link>
            </form>
        </div>
    );
};

export default Login;

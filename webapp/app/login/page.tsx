'use client'

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import axios from 'axios';
import Link from "next/link";

import api from '@/app/http/Client';
import styles from './page.module.css';

const GOOGLE_SSO_URL = process.env.NEXT_PUBLIC_GOOGLE_SSO_URL || 'http://localhost:8080/oauth/redirect';

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const router = useRouter();

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        setError('');

        try {
            const response = await api.post('/login', { email, password });
            if (response.status !== 200) {
                throw new Error('Invalid login credentials');
            }

            router.push('/');
        } catch (error: unknown) {
            if (axios.isAxiosError(error) && error.response) {
                setError('Invalid credentials');
            } else {
                setError(error instanceof Error ? error.message : 'An unexpected error occurred');
            }
        }
    }

    return (
        <div className={styles.loginContainer}>
            <form className={styles.loginForm} onSubmit={handleSubmit}>
                <label>
                    <span>Email</span>
                    <input
                        name="email"
                        type="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                        aria-label="Email address"
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
                        aria-label="Password"
                    />
                </label>
                {error && <p className={styles.error}>{error}</p>}
                <button type="submit" disabled={!email || !password}>Login</button>

                <div className={styles.googleSsoContainer}>
                    <a href={GOOGLE_SSO_URL}>
                        <button className={styles.googleSsoButton} type="button">
                            Sign in with Google
                        </button>
                    </a>
                </div>

                <Link href={'/password-forgotten'} className={styles.passwordForgotten}>Password Forgotten</Link>
            </form>
        </div>
    );
};

export default Login;
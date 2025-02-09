import React, { useCallback, useState } from 'react';
import Link from 'next/link';

import { ApplicationInterface } from '@/app/interfaces/Application';
import ApplicationDropdown from '@/app/components/registration/applicationDropdown';
import { deleteUserRegistration, getUserRegistration } from '@/app/services/userRegistrationService';
import { UserRegistrationInterface } from '@/app/interfaces/UserRegistration';

import styles from './registration.module.css';

interface RegistrationProps {
    userId: string;
    applications: ApplicationInterface[];
}

export const Registration: React.FC<RegistrationProps> = ({ userId, applications }) => {
    const [selectedApplication, setSelectedApplication] = useState<ApplicationInterface | null>(null);
    const [userRegistration, setUserRegistration] = useState<UserRegistrationInterface | null>(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const fetchUserRegistration = useCallback(async (applicationId: string) => {
        setLoading(true);
        setError(null);
        try {
            const registration = await getUserRegistration(userId, applicationId);
            setUserRegistration(registration);
        } catch (error: unknown) {
            setUserRegistration(null);
            setError("No registration found for this application.");
        }
        setLoading(false);
    }, [userId]);

    const handleApplicationChange = (application: ApplicationInterface) => {
        setSelectedApplication(application);
        fetchUserRegistration(application.id);
    };

    const handleUserRegistrationDelete = async (userRegistration: UserRegistrationInterface) => {
        await deleteUserRegistration(userId, userRegistration.applicationId);
        setUserRegistration(null);
    };

    return (
        <div className={styles.container}>
            <ApplicationDropdown
                applications={applications}
                selectedApplication={selectedApplication}
                onApplicationChange={handleApplicationChange}
            />

            {loading && <p className={styles.loading}>Loading registration...</p>}

            {error && <p className={styles.error}>{error}</p>}

            {userRegistration && (
                <div className={styles.registrationDetails}>
                    <p><strong>Username:</strong> {userRegistration.username}</p>
                    <p><strong>Roles:</strong> {userRegistration.roles.join(', ')}</p>
                    <p><strong>Preferred Languages:</strong> {userRegistration.preferredLanguages.join(', ')}</p>
                    <p><strong>Timezone:</strong> {userRegistration.timezone}</p>


                    <Link
                        href={`/userRegistrations/${userId}/${userRegistration.applicationId}/edit`}
                        className={styles.editButton}
                    >
                        Edit Registration
                    </Link>

                    <button
                        className={styles.deleteButton}
                        onClick={() => handleUserRegistrationDelete(userRegistration)}
                    >
                        Delete Registration
                    </button>
                </div>
            )}

            {selectedApplication?.id && !userRegistration && !loading && (
                <div className={styles.noRegistration}>
                    <p>User registration for this application not found.</p>
                    <Link
                        href={`/userRegistrations/${userId}/${selectedApplication.id}/create`}
                        className={styles.createButton}
                    >Create User Registration</Link>
                </div>
            )}
        </div>
    );
};
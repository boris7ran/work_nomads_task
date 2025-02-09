'use client';

import React from 'react';

import { ApplicationInterface } from "@/app/interfaces/Application";

import styles from './applicationDropdown.module.css';

interface ApplicationDropdownProps {
    applications: ApplicationInterface[];
    selectedApplication: ApplicationInterface | null;
    onApplicationChange: (application: ApplicationInterface) => void;
}

const ApplicationDropdown: React.FC<ApplicationDropdownProps> = ({ applications, selectedApplication, onApplicationChange }) => {
    const handleApplicationChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
        const selectedApplication = applications.find((app) => app.id === event.target.value);

        if (selectedApplication) {
            onApplicationChange(selectedApplication);
        }
    };

    return (
        <div className={styles.container}>
            <label className={styles.label} htmlFor="application-select">Select Application:</label>
            <select
                id="application-select"
                className={styles.dropdown}
                onChange={handleApplicationChange}
                value={selectedApplication?.id ?? ""}
                disabled={applications.length === 0}
            >
                <option value="" disabled>-- Choose an Application --</option>
                {applications.map((app) => (
                    <option key={app.id} value={app.id}>
                        {app.name}
                    </option>
                ))}
            </select>
        </div>
    );
};

export default ApplicationDropdown;
